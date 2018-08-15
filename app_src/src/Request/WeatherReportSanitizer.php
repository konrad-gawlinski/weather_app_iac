<?php

namespace App\Request;

use App\Request\WeatherReportParameter as Param;

class WeatherReportSanitizer
{
  public function sanitize(array $params)
  {
    $dateFilter = ['filter' => FILTER_VALIDATE_REGEXP,
      'options' => [
        'default' => null,
        'regexp' => '/\d{4}-\d{2}-\d{2}/'
    ]];

    $sanitizedParams = filter_var_array($params, [
      PARAM::PARAM_START_DATE => $dateFilter,
      PARAM::PARAM_END_DATE => $dateFilter,
      PARAM::PARAM_CITY => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => [
          'default' => null,
          'regexp' => '/[a-zA-Z\s]{2,}/'
        ]
      ],
      PARAM::PARAM_TEMP => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => [
          'default' => null,
          'regexp' => '/(lt|gt)[\-]?\d{1,2}(\.\d{1,2})?/'
        ]],
    ]);

    return array_filter($sanitizedParams);
  }
}