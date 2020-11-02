<?php

namespace WeatherFromJMA\Weather;

use DateTime;

class Sky extends WeatherInterface
{
  protected function setOverview(array $data): void
  {
    if (isset($data['value'])) :
      $this->value = $data['value'];
    else :
      $this->value = '';
    endif;
  }

  protected function setDetail(array $data): void
  {
    if (isset($data['value'])) :
      $this->value = $data['value'];
    else :
      $this->value = '';
    endif;
  }

  public function __get(string $name)
  {
    if ('value' === $name) :
      return $this->value;
    endif;
    return null;
  }
}
