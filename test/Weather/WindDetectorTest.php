<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\Wind;
use WeatherFromJMA\Weather\WindDetector;

class WindDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testDetectorFindData()
  {
    $weatherList = $this->LoadReport();
    $detector =  new WindDetector();
    $timeline = $detector->getTImeline($weatherList);
    $this->assertSame(8, $timeline->count());
    $this->assertSame(3, count($timeline->getOverviews()));

    foreach ($timeline as $weather):
      $this->assertSame('4', $weather->value);
      $this->assertSame('北東', $weather->direction);
      $this->assertSame('毎秒１０メートル以上', $weather->description);
    endforeach;

    foreach ($timeline->getOverviews() as $weather):
      $this->assertMatchesRegularExpression('/やや強く/', $weather->value);
    endforeach;
  }
}
