<?php

namespace App\Database;

abstract class BaseGateway
{
  /** @var \PDO */
  private $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo->pdo();
  }

  public function pdo()
  {
    return $this->pdo;
  }

  public function beginTransaction()
  {
    $this->pdo->beginTransaction();
  }

  public function commitTransaction()
  {
    $this->pdo->commit();
  }

  public function rollbackTransaction()
  {
    $this->pdo->rollBack();
  }
}