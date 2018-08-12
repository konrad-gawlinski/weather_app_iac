<?php

namespace App\Commands\DatabaseInit;

use App\Database\PDO;

class Executor
{
  private const INIT_SCRIPT_PATH = __DIR__.'/../../../script/database/init.sql';

  /** @var PDO */
  private $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function execute()
  {
    $sql = file_get_contents(self::INIT_SCRIPT_PATH);
    $this->pdo->pdo()->exec($sql);
  }
}