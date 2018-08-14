<?php

namespace App\Request;

class WeatherReportUtils
{
  public function mapParamsToCriteria(array $map, array $params)
  {
    $intersection = array_intersect_key($params, $map);

    $criteria = [];
    foreach($intersection as $param => $criteriaValue){
      $criteria[$map[$param]] = $criteriaValue;
    }

    return $criteria;
  }

}