<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WebController extends Controller
{
    /**
     * @Route("/", name="web", methods={"GET"})
     */
    public function index(EventRepository $eventRepository)
    {
       $events = $eventRepository->getEventToday();

       return $this->render("web/index.html.twig", [
           "events" => $events
       ]);
    }
}
