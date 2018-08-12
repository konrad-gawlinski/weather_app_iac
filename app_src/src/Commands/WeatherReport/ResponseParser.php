<?php

namespace App\Commands\WeatherReport;

use App\Database\Definition as DBColumn;

class ResponseParser
{
  private const KEY_TIMEZONE = 'timezone';
  private const KEY_DATA = 'data';
  private const KEY_DATETIME = 'datetime';
  private const KEY_TEMP = 'temp';
  private const KEY_MAX_TEMP = 'max_temp';
  private const KEY_MIN_TEMP = 'min_temp';

  /**
   * @throws \Exception
   */
  public function parse(string $response) : array
  {
    $report = json_decode($response, true);
    if (is_null($report)) throw new \Exception('Weather report api response is not proper JSON');

    return $this->parseReport($report);
  }

  private function parseReport(array $report) : array
  {
    if (empty($report)) return ['',[]];

    $result = [];
    $timezone = '';

    foreach ($report as $entry) {
      if ($entry) {
        $timezone = $entry[self::KEY_TIMEZONE];
        $data = reset($entry[self::KEY_DATA]);
        $result[] = [
          DBColumn::DATA_DATETIME => $data[self::KEY_DATETIME],
          DBColumn::DATA_TEMP => $data[self::KEY_TEMP],
          DBColumn::DATA_MAX_TEMP => $data[self::KEY_MAX_TEMP],
          DBColumn::DATA_MIN_TEMP => $data[self::KEY_MIN_TEMP],
        ];
      }
    }

    return [$timezone, $result];
  }
}