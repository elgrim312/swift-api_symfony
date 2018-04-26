<?php

namespace App\Controller\Api;

use App\Entity\Login;
use App\Form\LoginType;
use App\Repository\UserRepository;
use App\Service\CheckifUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class SecurityController
 * @package App\Controller\Api
 * @Route("/api")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function loginAction(Request $request, UserRepository $userRepository, SerializerInterface $serializer, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $data = $request->request->all();
        $login = new Login();
        $form = $this->createForm(LoginType::class, $login);
        $form->submit($data);

        if ($form->isValid()) {
            $user = $userRepository->findOneByEmail($login->getEmail());

            if (is_null($user)) {
                return new Response("User not found");
            }
            $validPassword =$userPasswordEncoder->isPasswordValid($user, $login->getPassword());
            if (!$validPassword) {
                return new Response("User not found", 200);
            }

            $json = $serializer->serialize(["token" => $user->getApiKey()], 'json');
            return new Response($json, 200);
        }
    }

    /**
     * @Route("/refreshToken", name="refresh_token", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function refreshAction(Request $request, UserRepository $userRepository,  SerializerInterface $serializer, CheckifUser $checkifUser)
    {
        $token = $request->request->get("token");
        $user = $checkifUser->getUser($token);
        $em = $this->getDoctrine()->getManager();

        if ($user === false) {
            return new Response("User not found", 200);
        }

        $user->setApiKey(uniqid());

        $em->persist($user);
        $em->flush();

        $json = $serializer->serialize(["token" => $user->getApiKey()], "json");

        return new Response($json, 200);
    }
}
