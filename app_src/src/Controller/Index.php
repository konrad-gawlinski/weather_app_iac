<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class Index
{
  /**
   * @Route("/", name="index")
   */
  function index()
  {
    return new JsonResponse([
      'hello' => 'world!'
    ]);
  }
}