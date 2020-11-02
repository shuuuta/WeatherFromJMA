<?php

namespace WeatherFromJMA\Weather;

use DateTime;

abstract class WeatherInterface
{
  protected DateTime $date;
  protected string $value;

  public function __construct(array $data, ?string $dataType = '')
  {
    $this->date = $data['date'];

    if ('overview' === $dataType) :
      $this->setOverview($data);
    else :
      $this->setDetail($data);
    endif;
  }

  public function getDate()
  {
    return $this->date;
  }

  abstract public function __get(string $name);
  abstract protected function setOverview(array $data);
  abstract protected function setDetail(array $data);
}
