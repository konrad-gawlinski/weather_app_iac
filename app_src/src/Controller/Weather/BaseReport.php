<?php

namespace App\Controller\Weather;

use App\Database\Gateway;
use App\Request\WeatherReportSanitizer as RequestSanitizer;
use App\Request\WeatherReportUtils;

class BaseReport
{
  /** @var Gateway */
  protected $dbGateway;

  /** @var RequestSanitizer */
  protected $sanitizer;

  /** @var WeatherReportUtils */
  protected $utils;

  public function setDatabaseGateway(Gateway $dbGateway)
  {
    $this->dbGateway = $dbGateway;
  }

  public function setRequestSanitizer(RequestSanitizer $sanitizer)
  {
    $this->sanitizer = $sanitizer;
  }

  public function setUtils(WeatherReportUtils $utils)
  {
    $this->utils = $utils;
  }
}