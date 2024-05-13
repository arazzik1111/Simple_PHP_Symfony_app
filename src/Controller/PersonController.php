<?php
// src/Controller/PersonController.php

declare(strict_types=1);

namespace App\Controller;

use App\Services\PersonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends AbstractController
{
    private $personService;

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    public function new(Request $request): Response
{
    $result = $this->personService->createPerson($request);

    if ($result instanceof \Symfony\Component\Form\FormInterface) {
        return $this->render('person/new.html.twig', [
            'form' => $result->createView(),
        ]);
    }

    return $this->redirectToRoute('people');
}

    public function index(): Response
    {
        $people = $this->personService->getPeople();

        return $this->render('person/index.html.twig', [
            'people' => $people,
        ]);
    }

    public function delete(Request $request, $id): Response
    {
        $person = $this->personService->getPerson($id);

        if (!$person) {
            throw $this->createNotFoundException('No person found for id '.$id);
        }

        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $this->personService->deletePerson($person);
        }

        return $this->redirectToRoute('people');
    }
}