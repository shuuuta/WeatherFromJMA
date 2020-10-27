<?php

namespace WeatherFromJMA\Weather;

use DateTime;

class Wave implements WeatherInterface
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

  public function addDetail(array $waveData): void
  {
    $this->details[] = [
      'condition' => isset($waveData['condition']) ? $waveData['condition'] : '',
      'value' => isset($waveData['value']) ? $waveData['value'] : '',
      'date' => $waveData['date'],
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
