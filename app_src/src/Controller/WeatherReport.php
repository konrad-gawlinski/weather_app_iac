<?php

namespace App\Controller;

use App\Database\Gateway;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WeatherReport
{
  private const ARG_START_DATE = 'start_date';
  private const ARG_END_DATE = 'end_date';
  private const ARG_COUNTRY_ID = 'country_id';
  private const ARG_TEMP = 'temp';
  /**
   * @Route("/weather-report", name="weather-report")
   */
  public function render(Request $request, Gateway $gateway)
  {
    $report = $gateway->fetchReportData();

    return new JsonResponse(
      $report
    );
  }
}