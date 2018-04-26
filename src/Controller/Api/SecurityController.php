<?php

namespace App\Controller\Api;

use App\Entity\Login;
use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

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
}
