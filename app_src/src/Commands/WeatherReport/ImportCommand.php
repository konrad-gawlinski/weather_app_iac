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

  /** @var RegionUtils */
  private $regionUtils;

  public function setExecutor(Executor $executor)
  {
    $this->executor = $executor;
  }

  public function setSettings(Settings $settings)
  {
    $this->settings = $settings;
  }

  public function setValidator(Validator $validator)
  {
    $this->validator = $validator;
  }

  public function setRegionUtils(RegionUtils $regionUtils)
  {
    $this->regionUtils = $regionUtils;
  }

  protected function configure()
  {
    $this
      ->setDescription('Import weather data.')
      ->setHelp('This command allows you to import and persist weather data')
    ;

    $this
      ->addArgument(self::COUNTRY_ARGUMENT, InputArgument::OPTIONAL, 'Country')
      ->addArgument(self::CITY_ARGUMENT, InputArgument::OPTIONAL, 'City')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $validRegions = $this->settings->getAllowedRegions();
    $this->validator->setValidRegions($validRegions);
    $errors = $this->validator->validate($input);
    if ($errors) {
      $this->outputErrors($errors, $output);
    } else {
      $country = $input->getArgument(ImportCommand::COUNTRY_ARGUMENT) ?: '';
      $city = $input->getArgument(ImportCommand::CITY_ARGUMENT) ?: '';

      $regionPairs = $this->regionUtils->getRegionPairs($country, $city, $validRegions);
      $this->executor->execute($output, $regionPairs);
    }
  }

  private function outputErrors(array $errors, OutputInterface $output)
  {
    $output->writeln('==================== Errors');
    $output->writeln($errors);
  }
}


