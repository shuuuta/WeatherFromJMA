<?php

namespace WeatherFromJMA\Weather;

class Temperature implements WeatherInterface
{
  private $details = [];

  public function addOverview(array $data): void
  {
    $this->overviews[] = [
      'type' => isset($data['type']) ? $data['type'] : '',
      'name' => isset($data['name']) ? $data['name'] : '',
      'value' => isset($data['value']) ? $data['value'] : '',
      'date' => $data['date'],
    ];
  }

  public function addDetail(array $data): void
  {
    $this->details[] = [
      'value' => isset($data['value']) ? $data['value'] : '',
      'date' => $data['date'],
    ];
  }
}
