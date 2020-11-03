<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\WaveDetector;

class WaveDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testDetectorFindData()
  {
    $weatherList = $this->LoadReport();
    $detector =  new WaveDetector();
    $timeline = $detector->getTImeline($weatherList);
    $this->assertSame(3, $timeline->count());
    $this->assertSame(3, count($timeline->getOverviews()));

    foreach ($timeline as $weather):
    $this->assertSame('3.0', $weather->value);
    endforeach;

    foreach ($timeline->getOverviews() as $weather):
      $this->assertMatchesRegularExpression('/メートル/', $weather->value);
    endforeach;
  }
}
