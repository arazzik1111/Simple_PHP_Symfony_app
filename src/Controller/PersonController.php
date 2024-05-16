<?php
// src/Controller/PersonController.php

declare(strict_types=1);

namespace App\Controller;

use App\Services\PersonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    private $personService;

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    public function index()
    {
        return $this->json($this->personService->getPeople());
    }

    public function new(Request $request)
    {
        return $this->personService->createPerson($request);
    }

    public function edit(Request $request, $id)
    {
        return $this->personService->updatePerson($request, $id);
    }

    public function delete($id)
    {
        return $this->personService->deletePerson($id);
    }

    public function patch(Request $request, $id)
    {
        return $this->personService->patchPerson($request, $id);
    }

    public function show($id)
    {
        return $this->personService->showPerson($id);
    }
}