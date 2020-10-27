<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\Sky;
use WeatherFromJMA\Weather\SkyDetector;

class SkyDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testGetWeatherReturnSkyClass()
  {
    $weatherList = $this->LoadReport();
    $detector =  new SkyDetector();
    $weather = $detector->getWeather($weatherList);

    $this->assertInstanceOf(Sky::class, $weather);
  }

  public function testReturnedWeatherHasCorrectData()
  {
  }
}
