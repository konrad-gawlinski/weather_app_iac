<?php

namespace App\Commands\WeatherReport;

class Settings
{
  public function getAllowedRegions()
  {
    return [
      'all' => ['all'],
      'DE' => ['Berlin'],
      'ES' => ['Madrid']
    ];
  }
}