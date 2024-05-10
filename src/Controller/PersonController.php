<?php
// src/Controller/PersonController.php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PersonController extends AbstractController
{
    /**
     * @Route("/people", name="people")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $people = $em->getRepository(Person::class)->findAll();

        return $this->render('person/index.html.twig', [
            'people' => $people,
        ]);
    }
}