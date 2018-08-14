<?php

namespace App\Database;

interface Definition
{
  const TABLE_REGION = 'region';
  const TABLE_DATA = 'data';
  const REGION_ID = 'id';
  const REGION_TIMEZONE = 'timezone';
  const REGION_COUNTRY = 'country';
  const REGION_CITY = 'city';

  const DATA_REGION_ID = 'region_id';
  const DATA_DATETIME = 'datetime';
  const DATA_TEMP = 'temp';
  const DATA_MAX_TEMP = 'max_temp';
  const DATA_MIN_TEMP = 'min_temp';

  const CRITERIA_START_DATE = 'start_date';
  const CRITERIA_END_DATE = 'end_date';
  const CRITERIA_CITY_ID = 'city_id';
  const CRITERIA_TEMP = 'temp';
}