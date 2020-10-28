<?php

namespace WeatherFromJMA\Weather;

class Rain implements WeatherInterface
{
  private $details = [];

  public function addDetail(array $waveData): void
  {
    $this->details[] = [
      'condition' => isset($waveData['condition']) ? $waveData['condition'] : '',
      'value' => isset($waveData['value']) ? $waveData['value'] : '',
      'date' => $waveData['date'],
    ];
  }
}
