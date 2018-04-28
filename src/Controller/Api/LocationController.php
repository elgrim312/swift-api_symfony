<?php

namespace App\Controller\Api;

use App\Entity\Rapport;
use App\Repository\EventRepository;
use App\Service\CheckifUser;
use DateTime;
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
     * @param EventRepository $eventRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function getLocationAction(EventRepository $eventRepository, SerializerInterface $serializer)
    {
        $events = $eventRepository->getCurrentEvent();

        $json = $serializer->serialize(["message" => "No event for this time"], "json");

        foreach ($events as $event) {
            $json = $serializer->serialize(["date" => $event->getStartAt()->format(DateTime::ATOM), "location" => $event->getLocation()->getDescription()], 'json');
        }

        return new Response($json,  200);

    }
}
