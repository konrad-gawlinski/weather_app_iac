<?php

namespace App\Request;

interface WeatherReportParameter
{
  const PARAM_START_DATE = 'start_date';
  const PARAM_END_DATE = 'end_date';
  const PARAM_CITY = 'city';
  const PARAM_TEMP = 'temp';
}