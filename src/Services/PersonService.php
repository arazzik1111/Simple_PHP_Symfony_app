<?php
// src/Services/PersonService.php

namespace App\Services;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonService
{
    private $entityManager;
    private $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    public function getPeople(): array
    {
        return $this->entityManager->getRepository(Person::class)->findAll();
    }

    public function createPerson(Request $request): Response
{
    $person = new Person();
    $result = $this->handleJson($request, $person);

    if ($result instanceof Person) {
        // Serialize the Person object to JSON
        $personJson = json_encode([
            'id' => $result->getId(),
            'name' => $result->getName(),
            'surname' => $result->getSurname(),
        ]);

        return new Response($personJson, Response::HTTP_CREATED);
    }

    return new Response(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
}

    public function updatePerson(Request $request, $id): Response
{
    $person = $this->getPerson($id);

    if (!$person) {
        return new Response(json_encode(['error' => 'No person found for id '.$id]), Response::HTTP_NOT_FOUND);
    }

    $result = $this->handleJson($request, $person);

    if ($result instanceof Person) {
        // Serialize the Person object to JSON
        $personJson = json_encode([
            'id' => $result->getId(),
            'name' => $result->getName(),
            'surname' => $result->getSurname(),
        ]);

        return new Response($personJson, Response::HTTP_OK);
    }

    return new Response(json_encode(['error' => 'Invalid data']), Response::HTTP_BAD_REQUEST);
}

    public function deletePerson($id): Response
{
    $person = $this->getPerson($id);

    if (!$person) {
        return new Response(json_encode(['error' => 'No person found for id '.$id]), Response::HTTP_NOT_FOUND);
    }

    $this->entityManager->remove($person);
    $this->entityManager->flush();

    return new Response(json_encode(['success' => 'Person deleted']), Response::HTTP_OK);
}

    private function getPerson($id): ?Person
    {
        return $this->entityManager->getRepository(Person::class)->find($id);
    }

    private function handleJson(Request $request, Person $person): ?Person
    {
        $data = json_decode($request->getContent(), true);

        if ($data && isset($data['name']) && isset($data['surname'])) {
            $person->setName($data['name']);
            $person->setSurname($data['surname']);

            $this->entityManager->persist($person);
            $this->entityManager->flush();

            return $person;
        }

        return null;
    }

    public function patchPerson(Request $request, $id): Response
    {
        $person = $this->getPerson($id);

        if (!$person) {
            return new Response(json_encode(['error' => 'No person found for id '.$id]), Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if ($data && isset($data['surname'])) {
            $person->setSurname($data['surname']);

            $this->entityManager->persist($person);
            $this->entityManager->flush();

            // Serialize the Person object to JSON
            $personJson = json_encode([
                'id' => $person->getId(),
                'name' => $person->getName(),
                'surname' => $person->getSurname(),
            ]);

            return new Response($personJson, Response::HTTP_OK);
        }

        return new Response(json_encode(['error' => 'Invalid data']), Response::HTTP_BAD_REQUEST);
    }

    public function showPerson($id): Response
    {
        $person = $this->getPerson($id);

        if (!$person) {
            return new Response(json_encode(['error' => 'No person found for id '.$id]), Response::HTTP_NOT_FOUND);
        }

        // Serialize the Person object to JSON
        $personJson = json_encode([
            'id' => $person->getId(),
            'name' => $person->getName(),
            'surname' => $person->getSurname(),
        ]);

        return new Response($personJson, Response::HTTP_OK);
    }
}
