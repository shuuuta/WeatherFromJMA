<?php

namespace WeatherFromJMA\Weather;

use DateTime;

class Wind implements WeatherInterface
{
  private $overviews = [];
  private $details = [];

  public function addOverview(string $value, DateTime $date): void
  {
    $this->overviews[] = [
      'value' => $value,
      'date' => $date,
    ];
  }

  public function addDetail(array $windData): void
  {
    $this->details[] = [
      'description' => isset($windData['description']) ? $windData['description'] : '',
      'speedLevel' => isset($windData['speedLevel']) ? $windData['speedLevel'] : '',
      'direction' => $windData['direction'],
      'date' => $windData['date'],
    ];
  }

  public function getDetails()
  {
  }

  public function getOverviews()
  {
  }

  // class名の取得(en ja)

  // minSpeed()
  // maxSpeed()
}
