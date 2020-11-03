<?php

namespace WeatherFromJMA\Weather;

class Temperature extends WeatherInterface
{
  private $details = [];

  public function setOverview(array $data): void
  {
    if (isset($data['title']) && isset($data['value'])) :
      $this->value = $data['title'] . $data['value'] . '℃';
    else :
      $this->value = '';
    endif;
  }

  public function setDetail(array $data): void
  {
    $this->value = $data['value'] ?? '';
  }

  public function __get(string $name)
  {
    if ('value' === $name) :
      return $this->value;
    endif;
    return null;
  }
}
