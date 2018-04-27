<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WebController extends Controller
{
    /**
     * @Route("/", name="web", methods={"GET"})
     */
    public function index()
    {
       $form = $this->createForm(LocationType::class , null , [
           'action' => $this->generateUrl('get_qrcode'),
           'method' => 'POST'
       ]);

       return $this->render("web/index.html.twig", [
           "form" => $form->createView()
       ]);
    }

    /**
     * @Route("/getQRCode", name="get_qrcode")
     * @param Request $resquest
     * @param LocationRepository $locationRepository
     * @param EntityManager $em
     * @throws \Doctrine\ORM\ORMException
     */
    public function getQRCode(Request $resquest, LocationRepository $locationRepository)
    {
        $locationId = $resquest->request->get('location')["description"];
        $location = $locationRepository->find($locationId);
        $em = $this->getDoctrine()->getManager();

        $date = new \DateTime();
        $name = $location->getDescription();

        $location->setQrcode($name. "".$date->getTimestamp().uniqid());

        $em->persist($location);
        $em->flush();


        return $this->render("web/qrcode.html.twig", [
            "qrcode_string" => $location->getQrcode(),
            "qrcode_id" => $location->getId()
        ]);

    }

    /**
     * @param Request $request
     * @param LocationRepository $locationRepository
     * @return Response
     * @Route("/reloadQrcode", name="reload_qrcode")
     */
    public function reloadQrcode(Request $request, LocationRepository $locationRepository)
    {
        $data = json_decode($request->getContent());
        $qrcode_id = $data->id_qrcode;
        $em = $this->getDoctrine()->getManager();

        $location = $locationRepository->find($qrcode_id);
        $date = new \DateTime();
        $name = $location->getDescription();

        $location->setQrcode($name. "".$date->getTimestamp().uniqid());
        $em->persist($location);
        $em->flush();

        return new Response("ok reload", 200);

    }
}
