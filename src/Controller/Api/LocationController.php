<?php

namespace App\Controller\Api;

use App\Entity\Rapport;
use App\Repository\EventRepository;
use App\Service\CheckifUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class LocationController
 * @package App\Controller\Api
 * @Route("/api")
 */
class LocationController extends Controller
{
    /**
     * @Route("/getLocation", name="get_location")
     */
    public function getLocationAction(Request $request, CheckifUser $checkifUser, EventRepository $eventRepository, SerializerInterface $serializer)
    {
        $token = $request->request->get("token");
        $user = $checkifUser->getUser($token);


        if ($user === false) {
            return new Response("User not found", 200);
        }

        $events = $eventRepository->getCurrentEvent();

        $json = "";

        foreach ($events as $event) {
            $json = $serializer->serialize(["date" => $event->getStartAt(), "location" => $event->getLocation()->getDescription()], 'json');
        }

        return new Response($json,  200);

    }
}
