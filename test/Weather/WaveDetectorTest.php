<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\Wave;
use WeatherFromJMA\Weather\WaveDetector;

class WaveDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testGetWeatherReturnWaveClass()
  {
    $weatherList = $this->LoadReport();
    $detector =  new WaveDetector();
    $weather = $detector->getWeather($weatherList);

    $this->assertInstanceOf(Wave::class, $weather);
  }

  public function testReturnedWeatherHasCorrectData()
  {
  }
}
