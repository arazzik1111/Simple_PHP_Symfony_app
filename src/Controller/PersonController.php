<?php
// src/Controller/PersonController.php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\PersonService;

class PersonController extends AbstractController
{
    private $personService;

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    public function index(): Response
    {
        $people = $this->personService->getPeople();

        return $this->json($people);
    }

    public function new(Request $request): Response
    {
        $person = new Person();
        $result = $this->personService->handleJson($request, $person);

        if ($result instanceof Person) {
            return $this->json($result, Response::HTTP_CREATED);
        }

        return $this->json(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    public function edit(Request $request, $id): Response
    {
        $person = $this->personService->getPerson($id);

        if (!$person) {
            return $this->json(['error' => 'No person found for id '.$id], Response::HTTP_NOT_FOUND);
        }

        $result = $this->personService->handleJson($request, $person);

        if ($result instanceof Person) {
            return $this->json($result, Response::HTTP_OK);
        }

        return $this->json(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    public function delete(Request $request, $id): Response
    {
        $person = $this->personService->getPerson($id);

        if (!$person) {
            return $this->json(['error' => 'No person found for id '.$id], Response::HTTP_NOT_FOUND);
        }

            $this->personService->deletePerson($person);
            return $this->json(['success' => 'Person deleted'], Response::HTTP_OK);

    }
}