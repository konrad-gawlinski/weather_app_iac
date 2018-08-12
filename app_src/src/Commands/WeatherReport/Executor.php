<?php

namespace App\Commands\WeatherReport;

use App\Database\Exception as DBException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client as HttpClient;
use App\Database\Gateway as DBGateway;

class Executor
{
  private const API_URL = 'https://yoc-media.github.io/weather/report';

  /** @var HttpClient */
  private $httpClient;

  /** @var ResponseParser */
  private $responseParser;

  /** @var DBGateway */
  private $dbGateway;

  public function __construct(HttpClient $httpClient, ResponseParser $responseParser, DBGateway $dbGateway)
  {
    $this->httpClient = $httpClient;
    $this->dbGateway = $dbGateway;
    $this->responseParser = $responseParser;
  }

  public function execute(InputInterface $input, OutputInterface $output)
  {
    $country = $input->getArgument(ImportCommand::COUNTRY_ARGUMENT);
    $city = $input->getArgument(ImportCommand::CITY_ARGUMENT);

    $output->writeln('Running import for following region: ' . "{$country}/{$city}");

    try {
      $totalInsertedEntries = $this->importReport($country, $city);
      $output->writeln("Total inserted rows: [{$totalInsertedEntries}]");
    } catch (DBException $e) {
      $this->dbGateway->rollbackTransaction();
      $output->writeln('Database error: ' . $e->getMessage());
    } catch (\Throwable $e) {
      $output->writeln('Error: ' . $e->getMessage());
    }
  }

  /**
   * @throws DBException
   * @throws \Exception
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  private function importReport(string $country, string $city) : int
  {
    $response = $this->httpClient->request('GET', self::API_URL."/{$country}/{$city}.json", []);
    if ($response->getStatusCode() != 200) throw new \Exception('Invalid response status');
    list($timezone, $weatherData) = $this->responseParser->parse($response->getBody());

    $totalInsertedEntries = 0;
    if ($timezone) {
      $this->dbGateway->beginTransaction();
      $this->dbGateway->insertRegion($timezone, $country, $city);
      $totalInsertedEntries = $this->dbGateway->insertData($country, $city, $weatherData);
      $this->dbGateway->commitTransaction();
    }

    return $totalInsertedEntries;
  }
}