<?php

namespace WeatherFromJMA\Weather;

use DateTime;

class Wave extends WeatherInterface
{
  public function setOverview(array $data): void
  {
    $this->value = $data['value']??'';
  }

  public function setDetail(array $data): void
  {
    $this->value = $data['value'] ?? '';
  }

  public function __get(string $name)
  {
    switch ($name):
      case 'value':
        return $this->value;
        break;
      default:
        return null;
    endswitch;
  }
}
