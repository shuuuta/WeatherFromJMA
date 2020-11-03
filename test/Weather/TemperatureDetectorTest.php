<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\TemperatureDetector;

class TemperatureDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testDetectorFindData()
  {
    $weatherList = $this->LoadReport();
    $detector =  new TemperatureDetector();
    $timeline = $detector->getTImeline($weatherList);
    $this->assertSame(9, $timeline->count());
    $this->assertSame(4, count($timeline->getOverviews()));

    foreach ($timeline as $weather):
      $this->assertTrue(19 <= (int) $weather->value);
    endforeach;

    foreach ($timeline->getOverviews() as $weather):
      $this->assertMatchesRegularExpression('/気温/', $weather->value);
    endforeach;
  }
}
