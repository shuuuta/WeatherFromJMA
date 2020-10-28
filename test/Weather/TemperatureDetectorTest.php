<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\Temperature;
use WeatherFromJMA\Weather\TemperatureDetector;

class TemperatureDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testGetWeatherReturnTemperatureClass()
  {
    $weatherList = $this->LoadReport();
    $detector =  new TemperatureDetector();
    $weather = $detector->getWeather($weatherList);

    var_dump($weather);
    $this->assertInstanceOf(Temperature::class, $weather);
  }

  public function testReturnedWeatherHasCorrectData()
  {
  }
}
