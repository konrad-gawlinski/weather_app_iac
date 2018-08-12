<?php

namespace App\Database;

class Gateway extends BaseGateway
{
  /**
   * @throws Exception
   */
  public function insertRegion(string $timezone, string $country, string $city)
  {
    $query = sprintf('INSERT IGNORE INTO %1$s (%2$s, %3$s, %4$s) VALUES (?, ?, ?)',
      Definition::TABLE_REGION,
      Definition::REGION_TIMEZONE, Definition::REGION_COUNTRY, Definition::REGION_CITY
    );

    $stmt = $this->pdo()->prepare($query);
    $isSuccess = $stmt->execute([$timezone, $country, $city]);

    if (!$isSuccess) throw new Exception("Insert region failed: [{$timezone}, {$country}, {$city}");
  }

  /**
   * @throws Exception
   */
  public function fetchRegion(string $country, string $city) : array
  {
    $query = sprintf('SELECT * FROM %1$s WHERE %2$s = ? AND %3$s = ?',
      Definition::TABLE_REGION,
      Definition::REGION_COUNTRY, Definition::REGION_CITY
    );

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
    $query = sprintf('INSERT IGNORE INTO %1$s (%2$s, %3$s, %4$s, %5$s, %6$s) VALUES %7$s',
      Definition::TABLE_DATA,
      Definition::DATA_REGION_ID, Definition::DATA_DATETIME,
      Definition::DATA_TEMP, Definition::DATA_MAX_TEMP, Definition::DATA_MIN_TEMP,
      $valuesListQuery
    );

    $stmt = $this->pdo()->prepare($query);
    $stmt->execute();

    return $stmt->rowCount();
  }

  private function prepareDataInsertValuesList(int $regionId, array $dataCollection)
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
}