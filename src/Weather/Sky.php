<?php

namespace WeatherFromJMA\Weather;

use DateTime;

class Sky implements WeatherInterface
{
  private $overviews = [];
  private $details = [];

  public function addOverview(string $value, DateTime $date) :void
  {
    $this->overviews[] = [
      'value' => $value,
      'date' => $date,
    ];
  }

  public function addDetail(string $value, DateTime $date) :void
  {
    $this->details[] = [
      'value' => $value,
      'date' => $date,
    ];
  }

  public function getDetails()
  {
  }

  public function getOverviews()
  {
  }

  // class名の取得(en ja)
}
