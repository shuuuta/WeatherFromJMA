<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\Rain;
use WeatherFromJMA\Weather\RainDetector;

class RainDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testGetWeatherReturnRainClass()
  {
    $weatherList = $this->LoadReport();
    $detector =  new RainDetector();
    $weather = $detector->getWeather($weatherList);

    $this->assertInstanceOf(Rain::class, $weather);
  }

  public function testReturnedWeatherHasCorrectData()
  {
  }
}
