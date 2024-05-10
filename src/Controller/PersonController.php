<?php
// src/Controller/PersonController.php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\PersonType;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends AbstractController
{
    /**
     * @Route("/people/new", name="person_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('people');
        }

        return $this->render('person/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/people", name="people", methods={"GET"})
     */
    public function index(EntityManagerInterface $em): Response
    {
        $people = $em->getRepository(Person::class)->findAll();

        return $this->render('person/index.html.twig', [
            'people' => $people,
        ]);
    }
  /**
 * @Route("/people/{id}/edit", name="person_edit", methods={"GET","POST"})
 */
public function edit(Request $request, $id, EntityManagerInterface $em): Response
{
    $person = $em->getRepository(Person::class)->find($id);

    if (!$person) {
        throw $this->createNotFoundException('No person found for id '.$id);
    }

    $form = $this->createForm(PersonType::class, $person);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();

        return $this->redirectToRoute('people');
    }

    return $this->render('person/edit.html.twig', [
        'person' => $person,
        'form' => $form->createView(),
    ]);
}
/**
 * @Route("/people/{id}/delete", name="person_delete", methods={"POST"})
 */
public function delete(Request $request, $id, EntityManagerInterface $em): Response
{
    $person = $em->getRepository(Person::class)->find($id);

    if (!$person) {
        throw $this->createNotFoundException('No person found for id '.$id);
    }

    if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
        $em->remove($person);
        $em->flush();
    }

    return $this->redirectToRoute('people');
}

}
