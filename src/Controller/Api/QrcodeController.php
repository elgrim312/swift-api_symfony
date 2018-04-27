<?php

namespace App\Controller\Api;

use App\Entity\Location;
use App\Entity\Rapport;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use App\Service\CheckifUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class QrcodeController
 * @package App\Controller
 * @Route("/api")
 */
class QrcodeController extends Controller
{
    /**
     * @Route("/checkIn", name="qrcode_check", methods={"POST"})
     * @param Request $request
     * @param LocationRepository $locationRepository
     * @param SerializerInterface $serializer
     * @param CheckifUser $checkifUser
     * @return Response
     */
    public function QrcodeCheckAction(Request $request, LocationRepository $locationRepository, SerializerInterface $serializer, CheckifUser $checkifUser, EventRepository $eventRepository)
    {
        $datas =json_decode($request->getContent());
        $location = $locationRepository->checkQrcode($datas->QRCodeData, $datas->beaconCollection);
        $user = $checkifUser->getUser($datas->token);
        $em = $this->getDoctrine()->getManager();

        if (count($location) == 0 ) {
            $json = $serializer->serialize(["response" => "KO"], "json");
            return new Response($json, 200);
        }

        if ($user === false) {
            $json = $serializer->serialize(["response" => 'User not found'], 'json');
            return new Response($json, 200);
        }

        $report = new Rapport();

        $event = $eventRepository->find($location[0]->getEvents()->getOwner()->getId());

        $report->setEvent($event);
        $report->setUser($user);
        $report->setIsValid(true);

        $em->persist($report);
        $em->flush();

        $json = $serializer->serialize(["response" => "OK"], "json");

        return new Response($json, 200);
    }
}
