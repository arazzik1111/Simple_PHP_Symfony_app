<?php
// src/Services/PersonService.php

namespace App\Services;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class PersonService
{
    private $entityManager;
    private $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    public function createPerson(Request $request) : String_
{
    $person = new Person();
    $form = $this->formFactory->createBuilder()
        ->add('name', TextType::class)
        ->add('surname', TextType::class)
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        // Set the 'name' and 'surname' fields of the Person object
        $person->setName($data['name']);
        $person->setSurname($data['surname']);

        $this->entityManager->persist($person);
        $this->entityManager->flush();

        return $person;
    }

    return $form;
}
public function handleJson(Request $request, Person $person)
{
    $data = json_decode($request->getContent(), true);

    // Log the request data
    error_log('Request data: ' . print_r($data, true));

    // Check if 'name' and 'surname' fields exist
    if ($data && isset($data['name']) && isset($data['surname'])) {
        $person->setName($data['name']);
        $person->setSurname($data['surname']);

        $this->entityManager->persist($person);
        $this->entityManager->flush();

        return $person;
    }

    // Return an error message if 'name' or 'surname' field is missing
    return ['error' => 'The name and surname fields are required.'];
}

    public function getPeople()
    {
        return $this->entityManager->getRepository(Person::class)->findAll();
    }

    public function getPerson($id)
    {
        return $this->entityManager->getRepository(Person::class)->find($id);
    }

    public function deletePerson(Person $person)
    {
        $this->entityManager->remove($person);
        $this->entityManager->flush();
    }
}