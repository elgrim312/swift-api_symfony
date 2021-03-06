<?php

namespace App\Controller;

use App\Entity\Monster;
use App\Form\MonsterType;
use App\Repository\MonsterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/monster")
 */
class MonsterController extends Controller
{
    /**
     * @Route("/", name="monster_index", methods="GET")
     */
    public function index(MonsterRepository $monsterRepository): Response
    {
        return $this->render('monster/index.html.twig', ['monsters' => $monsterRepository->findAll()]);
    }

    /**
     * @Route("/new", name="monster_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $monster = new Monster();
        $form = $this->createForm(MonsterType::class, $monster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($monster);
            $em->flush();

            return $this->redirectToRoute('monster_index');
        }

        return $this->render('monster/new.html.twig', [
            'monster' => $monster,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="monster_show", methods="GET")
     */
    public function show(Monster $monster): Response
    {
        return $this->render('monster/show.html.twig', ['monster' => $monster]);
    }

    /**
     * @Route("/{id}/edit", name="monster_edit", methods="GET|POST")
     */
    public function edit(Request $request, Monster $monster): Response
    {
        $form = $this->createForm(MonsterType::class, $monster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('monster_edit', ['id' => $monster->getId()]);
        }

        return $this->render('monster/edit.html.twig', [
            'monster' => $monster,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="monster_delete", methods="DELETE")
     */
    public function delete(Request $request, Monster $monster): Response
    {
        if ($this->isCsrfTokenValid('delete'.$monster->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($monster);
            $em->flush();
        }

        return $this->redirectToRoute('monster_index');
    }
}
