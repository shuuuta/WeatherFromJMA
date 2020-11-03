<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\RainDetector;

class RainDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testDetectorFindData()
  {
    $weatherList = $this->LoadReport();
    $detector =  new RainDetector();
    $timeline = $detector->getTImeline($weatherList);
    $this->assertSame(6, $timeline->count());
    $this->assertSame(6, count($timeline->getOverviews()));

    foreach ($timeline as $weather):
      $this->assertSame('雨', $weather->condition);
      $this->assertTrue(20 <= (int) $weather->value);
    endforeach;

    foreach ($timeline->getOverviews() as $weather):
      $this->assertMatchesRegularExpression('/雨の確率/', $weather->value);
    endforeach;
  }
}
