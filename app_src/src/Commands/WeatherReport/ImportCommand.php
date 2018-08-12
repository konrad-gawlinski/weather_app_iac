<?php

namespace App\Commands\WeatherReport;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
  const COUNTRY_ARGUMENT = 'country';
  const CITY_ARGUMENT = 'city';

  /** @var Executor */
  private $executor;

  /** @var Settings */
  private $settings;

  /** @var Validator */
  private $validator;

  public function __construct(Executor $executor, Settings $settings, Validator $validator)
  {
    parent::__construct();
    $this->executor = $executor;
    $this->settings = $settings;
    $this->validator = $validator;
  }

  protected function configure()
  {
    $this
      ->setDescription('Import weather data.')
      ->setHelp('This command allows you to import and persist weather data')
    ;

    $this
      ->addArgument(self::COUNTRY_ARGUMENT, InputArgument::REQUIRED, 'Country')
      ->addArgument(self::CITY_ARGUMENT, InputArgument::REQUIRED, 'City')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->validator->setValidRegions($this->settings->getAllowedRegions());
    $errors = $this->validator->validate($input);
    if ($errors) {
      $this->outputErrors($errors, $output);
    } else {
      $this->executor->execute($input, $output);
    }
  }

  private function outputErrors(array $errors, OutputInterface $output)
  {
    $output->writeln('==================== Errors');
    $output->writeln($errors);
  }
}


