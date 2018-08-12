<?php

namespace App\Database;

class PDO
{
  private $pdo;

  public function __construct(string $host, string $user, string $pass, string $database = '')
  {
    $this->pdo = new \PDO("mysql:dbname={$database};host={$host}", $user, $pass);
  }

  public function pdo() : \PDO
  {
    return $this->pdo;
  }
}