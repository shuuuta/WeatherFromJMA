<?php

namespace WeatherFromJMA\Weather;

use DateTime;

class Wind extends WeatherInterface
{
  private string $direction = '';
  private string $description = '';

  public function setOverview(array $data): void
  {
    $this->value = $data['value']??'';
  }

  public function setDetail(array $data): void
  {
    $this->value = $data['value'] ?? '';
    $this->direction = $data['direction'] ?? '';
    $this->description = $data['description'] ?? '';
  }

  public function __get(string $name)
  {
    switch ($name):
      case 'value':
        return $this->value;
        break;
      case 'direction':
        return $this->direction;
        break;
      case 'description':
        return $this->description;
        break;
      default:
        return null;
    endswitch;
  }
}
