<?php

namespace App\Request;

interface WeatherReportParameter
{
  const PARAM_START_DATE = 'start_date';
  const PARAM_END_DATE = 'end_date';
  const PARAM_CITY_ID = 'city_id';
  const PARAM_TEMP = 'temp';
}