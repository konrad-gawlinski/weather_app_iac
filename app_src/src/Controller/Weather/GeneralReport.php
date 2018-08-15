<?php

namespace App\Controller\Weather;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Database\Definition as Criteria;
use App\Request\WeatherReportParameter as Param;

class GeneralReport extends BaseReport
{
  /**
   * @Route("/weather-report", name="weather-report")
   */
  public function render(Request $request)
  {
    $criteria = $this->sanitizer->sanitize($request->query->all());
    $criteria = $this->utils->mapParamsToCriteria($this->getParamsToCriteriaMap(), $criteria);
    $report = $this->dbGateway->fetchReportData($criteria);

    return new JsonResponse(
      $report
    );
  }

  private function getParamsToCriteriaMap() : array
  {
    return [
      Param::PARAM_START_DATE => Criteria::CRITERIA_START_DATE,
      Param::PARAM_END_DATE => Criteria::CRITERIA_END_DATE,
      Param::PARAM_TEMP => Criteria::CRITERIA_TEMP,
      Param::PARAM_CITY => Criteria::CRITERIA_CITY,
    ];
  }
}