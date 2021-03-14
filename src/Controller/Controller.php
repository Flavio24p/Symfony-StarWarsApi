<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        return new JsonResponse([
            'success' => true,
            'message' => ['Hello Coding Challange!']
        ]);
    }
}