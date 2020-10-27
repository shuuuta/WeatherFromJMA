<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\Wind;
use WeatherFromJMA\Weather\WindDetector;

class WindDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testGetWeatherReturnWindClass()
  {
    $weatherList = $this->LoadReport();
    $detector =  new WindDetector();
    $weather = $detector->getWeather($weatherList);

    $this->assertInstanceOf(Wind::class, $weather);
  }

  public function testReturnedWeatherHasCorrectData()
  {
  }
}
