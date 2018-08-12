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
    $dsn = 'mysql:dbname=;host=weather_app_database';
    $user = 'weatherman';
    $password = 'admin';

      $dbh = new \PDO($dsn, $user, $password);
//    $dbConn = new \mysqli("weather_app_database", "weatherman", "admin", "");
    return new JsonResponse([
      'hello' => 'world!'
    ]);
  }
}