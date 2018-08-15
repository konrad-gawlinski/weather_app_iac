<?php

namespace App\Database;

use App\Database\Definition as Criteria;
use App\Database\Definition as DB;

class CriteriaQueryBuilder
{
  public function buildQuery(array $criteria) : string
  {
    $conditions = [];

    if (isset($criteria[Criteria::CRITERIA_START_DATE]))
      $conditions[] = sprintf('%s > \'%s\'', DB::DATA_DATETIME, $criteria[Criteria::CRITERIA_START_DATE]);

    if (isset($criteria[Criteria::CRITERIA_END_DATE]))
      $conditions[] = sprintf('%s < \'%s\'', DB::DATA_DATETIME, $criteria[Criteria::CRITERIA_END_DATE]);

    if (!isset($criteria[Criteria::CRITERIA_START_DATE]) && !isset($criteria[Criteria::CRITERIA_END_DATE]))
        $conditions[] = sprintf('%s > CURDATE() - INTERVAL 7 DAY', DB::DATA_DATETIME);
          
    if (isset($criteria[Criteria::CRITERIA_CITY]))
      $conditions[] = sprintf('%s = \'%s\'', DB::REGION_CITY, $criteria[Criteria::CRITERIA_CITY]);

    if (isset($criteria[Criteria::CRITERIA_TEMP])) {
      $operator = substr($criteria[Criteria::CRITERIA_TEMP], 0, 2);
      $operator = $operator === 'lt' ? '<' : '>';
      $temp = substr($criteria[Criteria::CRITERIA_TEMP], 2);

      $conditions[] = sprintf('%s %s %s', DB::DATA_TEMP, $operator, $temp);
    }

    if (!$conditions) return '';
    return implode(' AND ', $conditions);
  }
}