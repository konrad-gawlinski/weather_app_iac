<?php

namespace App\Controller\Weather;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Database\Definition as Criteria;
use App\Request\WeatherReportParameter as Param;

class AverageTempReport extends BaseReport
{
  /**
   * @Route("/weather-avg-temp", name="weather-avg-temp")
   */
  public function render(Request $request)
  {
    $criteria = $this->sanitizer->sanitize($request->query->all());
    $criteria = $this->utils->mapParamsToCriteria($this->getParamsToCriteriaMap(), $criteria);
    $report = $this->dbGateway->fetchReportAverageTemp($criteria);

    return new JsonResponse(
      $report
    );
  }

  private function getParamsToCriteriaMap() : array
  {
    return [
      Param::PARAM_START_DATE => Criteria::CRITERIA_START_DATE,
      Param::PARAM_END_DATE => Criteria::CRITERIA_END_DATE
    ];
  }
}