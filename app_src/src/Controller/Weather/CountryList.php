<?php

namespace App\Controller\Weather;

use App\Database\Gateway;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CountryList
{
  /** @var Gateway */
  protected $dbGateway;

  /**
   * @Route("/weather-country-list", name="weather-country-list")
   */
  public function render()
  {
    $report = $this->dbGateway->fetchCountryList();

    return new JsonResponse(
      $report
    );
  }

  public function setDatabaseGateway(Gateway $dbGateway)
  {
    $this->dbGateway = $dbGateway;
  }
}