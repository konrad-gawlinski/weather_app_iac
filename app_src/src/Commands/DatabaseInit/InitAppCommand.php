<?php

namespace App\Commands\DatabaseInit;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitAppCommand extends Command
{
  const COUNTRY_ARGUMENT = 'country';
  const CITY_ARGUMENT = 'city';

  /** @var Executor */
  private $executor;

  public function __construct(Executor $executor)
  {
    parent::__construct();
    $this->executor = $executor;
  }

  protected function configure()
  {
    $this
      ->setDescription('Create database and tables required by weather app.')
      ->setHelp('This command allows you to initialize weather app database')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->executor->execute();
  }

}


