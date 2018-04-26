<?php

namespace App\Controller\Api;

use App\Service\CheckifUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function getLocationAction(Request $request, CheckifUser $checkifUser)
    {
        $token = $request->request->get("token");
        $user = $checkifUser->getUser($token);

        if ($user === false) {
            return new Response("User not found", 200);
        }


    }
}