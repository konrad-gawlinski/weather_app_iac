<?php

namespace App\Commands\WeatherReport;

class RegionUtils
{
  public function getRegionPairs(string $country, string $city, array $validRegions)
  {
    if ($country && $city) return [[$country, $city]];
    if ($country) return $this->getRegionPairsByCountry($country, $validRegions);
    return $this->getAllRegionPairs($validRegions);
  }

  public function getRegionPairsByCountry(string $country, array $validRegions)
  {
    $regionPairs = [];
    foreach($validRegions[$country] as $city)
      $regionPairs[] = [$country, $city];

    return $regionPairs;
  }

  public function getAllRegionPairs(array $validRegions)
  {
    $regionPairs = [];
    foreach (array_keys($validRegions) as $country)
      $regionPairs = array_merge($regionPairs, $this->getRegionPairsByCountry($country, $validRegions));

    return $regionPairs;
  }
}