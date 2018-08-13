<?php

namespace App\Commands\WeatherReport;

class Settings
{
  public function getAllowedRegions()
  {
    return [
      'DE' => ['Berlin', 'Dusseldorf'],
      'AT' => ['Vienna'],
      'ES' => ['Madrid'],
      'NL' => ['Amsterdam'],
      'PL' => ['Warsaw'],
      'UK' => ['London'],
    ];
  }
}