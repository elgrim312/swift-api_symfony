<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 24/04/18
 * Time: 16:29
 */

namespace App\Controller\Api;


use App\Entity\Monster;
use App\Repository\MonsterRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Monster
 * @package App\Controller\Api
 * @Route("/api/monster")
 */

class MonsterController extends Controller
{
    /**
     * @Route("/", name="monster_api")
     * @param SerializerInterface $serializer
     * @param MonsterRepository $monsterRepository
     *
     * @return bool|float|int|string
     */
    public function getMonster(SerializerInterface $serializer, MonsterRepository $monsterRepository)
    {
        $monsters = $monsterRepository->findAll();
        $json = $serializer->serialize($monsters, 'json');

        return  new Response($json, 200);
    }

    /**
     * @Route("/qrcode", name="qrcode_api")
     * @param MonsterRepository $monsterRepository
     * @return Response
     */
    public function getQrcode(MonsterRepository $monsterRepository)
    {
        $monster = $monsterRepository->findAll();

        return $this->render("monster/qrcod.html.twig", [
            "monsters" => $monster
        ]);
    }

    /**
     * @Route("/qrcode/{slug}", name="qrcode_name_api")
     * @param $slug
     * @return Response
     */
    public function autogenerate($slug)
    {
        return $this->render("monster/qrcode_show.html.twig", [
            "slug" => $slug
        ]);
    }

    /**
     * @Route("/{id}")
     * @param Monster $monster
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function showMonster(Monster $monster, SerializerInterface $serializer)
    {
        $data = $serializer->serialize($monster, 'json');

        return new Response($data, 200);
    }
}