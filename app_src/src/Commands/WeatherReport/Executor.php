<?php

namespace App\Commands\WeatherReport;

use App\Database\Exception as DBException;
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

  public function setHttpClient(HttpClient $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  public function setResponseParser(ResponseParser $responseParser)
  {
    $this->responseParser = $responseParser;
  }

  public function setDatabaseGateway(DBGateway $dbGateway)
  {
    $this->dbGateway = $dbGateway;
  }

  public function execute(OutputInterface $output, array $regionPairs)
  {
    try {
      foreach ($regionPairs as $region) {
        list($country, $city) = $region;
        $output->writeln('Running import for following region: ' . "{$country}/{$city}");
        $totalInsertedEntries = $this->importReport($country, $city);
        $output->writeln("Total inserted rows: [{$totalInsertedEntries}]");
      }
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