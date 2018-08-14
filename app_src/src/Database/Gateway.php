<?php

namespace App\Database;

class Gateway extends BaseGateway
{
  /** @var CriteriaQueryBuilder */
  private $criteriaQueryBuilder;

  public function setCriteriaQueryBuilder(CriteriaQueryBuilder $builder)
  {
    $this->criteriaQueryBuilder = $builder;
  }

  /**
   * @throws Exception
   */
  public function insertRegion(string $timezone, string $country, string $city)
  {
    $df = $this->getDefinitionMap();
    $query = '
      INSERT IGNORE INTO %regionTable (%timezoneColumn, %countryColumn, %cityColumn)
        VALUES (?, ?, ?);
    ';
    $query = str_replace(array_keys($df), array_values($df), $query);

    $stmt = $this->pdo()->prepare($query);
    $isSuccess = $stmt->execute([$timezone, $country, $city]);

    if (!$isSuccess) throw new Exception("Insert region failed: [{$timezone}, {$country}, {$city}");
  }

  /**
   * @throws Exception
   */
  public function fetchRegion(string $country, string $city) : array
  {
    $df = $this->getDefinitionMap();
    $query = '
      SELECT * FROM %regionTable WHERE %countryColumn = ? AND %cityColumn = ?;
    ';
    $query = str_replace(array_keys($df), array_values($df), $query);

    $stmt = $this->pdo()->prepare($query);
    $isSuccess = $stmt->execute([$country, $city]);

    if (!$isSuccess) throw new Exception("Could not fetch region: [{$country}, {$city}]");

    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    if ($result === false) return [];

    return $result;
  }

  /**
   * @throws Exception
   */
  public function insertData(string $country, string $city, array $dataCollection) : int
  {
    $region = $this->fetchRegion($country, $city);
    if (!$region) throw new Exception("Region does not exist: [{$country}, {$city}");

    $regionId = intval($region[Definition::REGION_ID]);
    $valuesListQuery = $this->prepareDataInsertValuesList($regionId, $dataCollection);
    $df = $this->getDefinitionMap();
    $query = 'INSERT IGNORE INTO %dataTable (
      %regionIdColumn, %datetimeColumn, %tempColumn, %maxTempColumn, %minTempColumn)
      VALUES %s
    ';
    $query = str_replace(array_keys($df), array_values($df), $query);
    $query = sprintf($query, $valuesListQuery);

    $stmt = $this->pdo()->prepare($query);
    $stmt->execute();

    return $stmt->rowCount();
  }

  private function prepareDataInsertValuesList(int $regionId, array $dataCollection) : string
  {
    $valuesListQuery = '';
    $valuesTpl = '(%d, %s, %s, %s, %s),';
    foreach ($dataCollection as $entry) {
      $valuesListQuery .= sprintf($valuesTpl,
        $regionId, $this->pdo()->quote($entry[Definition::DATA_DATETIME]),
        $entry[Definition::DATA_TEMP], $entry[Definition::DATA_MAX_TEMP], $entry[Definition::DATA_MIN_TEMP]
      );
    }

    return substr($valuesListQuery, 0, -1);
  }

  public function fetchReportData(array $criteria = []) : array
  {
    return $this->fetchReport($this->buildReportDataQuery($criteria));
  }

  public function fetchReportAverageTemp(array $criteria)
  {
    return $this->fetchReport($this->buildAverageTempQuery($criteria));
  }

  private function fetchReport(string $query) : array
  {
    $stmt = $this->pdo()->prepare($query);
    $isSuccess = $stmt->execute();

    if (!$isSuccess) return [];

    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    if ($result === false) return [];

    return $result;
  }

  private function buildReportDataQuery(array $criteria)
  {
    $df = $this->getDefinitionMap();

    $query = 'SELECT %datetimeColumn, %countryColumn, %cityColumn, %tempColumn
      FROM %regionTable JOIN %dataTable ON %idColumn = %regionIdColumn';
    $query = str_replace(array_keys($df), array_values($df), $query);

    $conditions = $this->criteriaQueryBuilder->buildQuery($criteria);
    if ($conditions) $query .= ' WHERE '. $conditions;

    return $query;
  }

  private function buildAverageTempQuery(array $criteria)
  {
    $df = $this->getDefinitionMap();

    $query = 'SELECT %countryColumn, %cityColumn, AVG(%tempColumn) as average_temp
      FROM %regionTable JOIN %dataTable ON %idColumn = %regionIdColumn
      %s GROUP BY %countryColumn, %cityColumn
    ';
    $query = str_replace(array_keys($df), array_values($df), $query);

    $conditions = $this->criteriaQueryBuilder->buildQuery($criteria);
    if ($conditions) $query = sprintf($query, ' WHERE '.$conditions);
    else $query = sprintf($query, '');

    return $query;
  }

  public function fetchCountryList()
  {
    $df = $this->getDefinitionMap();
    $query = 'SELECT %idColumn, %countryColumn, %cityColumn FROM %regionTable;';
    $query = str_replace(array_keys($df), array_values($df), $query);

    return $this->fetchReport($query);
  }

  private function getDefinitionMap() : array
  {
    return [
      '%regionTable' => Definition::TABLE_REGION,
      '%dataTable' => Definition::TABLE_DATA,
      '%idColumn' => Definition::REGION_ID,
      '%timezoneColumn' => Definition::REGION_TIMEZONE,
      '%countryColumn' => Definition::REGION_COUNTRY,
      '%cityColumn' => Definition::REGION_CITY,
      '%regionIdColumn' => Definition::DATA_REGION_ID,
      '%datetimeColumn' => Definition::DATA_DATETIME,
      '%tempColumn' => Definition::DATA_TEMP,
      '%maxTempColumn' => Definition::DATA_MAX_TEMP,
      '%minTempColumn' => Definition::DATA_MIN_TEMP
    ];
  }
}