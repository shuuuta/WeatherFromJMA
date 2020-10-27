<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\WindDetector;

class WindDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testGetWeather()
  {
    $weatherList = $this->LoadReport();
    $detector =  new WindDetector();
    $weather = $detector->getWeather($weatherList);

    var_dump($weather);
  }
}
