<?php

namespace App\Commands\WeatherReport;

use Symfony\Component\Console\Input\InputInterface;

class Validator
{
  private const ERR_INVALID_COUNTRY = '101';
  private const ERR_INVALID_CITY = '102';

  private $validRegions;

  public function setValidRegions(array $validRegions)
  {
    $this->validRegions = $validRegions;
  }

  public function validate(InputInterface $input) : array
  {
    $errorMessages = [];
    $country = $input->getArgument(ImportCommand::COUNTRY_ARGUMENT);
    $city = $input->getArgument(ImportCommand::CITY_ARGUMENT);

    if (!isset($this->validRegions[$country])) {
      $errorMessages[] = $this->getErrorMessage(self::ERR_INVALID_COUNTRY);
    } else {
      if (!in_array($city, $this->validRegions[$country])) {
        $errorMessages[] = $this->getErrorMessage(self::ERR_INVALID_CITY);
      }
    }

    return $errorMessages;
  }

  private function getErrorMessage(string $key) : string
  {
    static $errorMessages = [
      self::ERR_INVALID_COUNTRY => 'Country is invalid',
      self::ERR_INVALID_CITY => 'City is invalid'
    ];

    return 'Error: '. $key .' Message: '. $errorMessages[$key];
  }
}