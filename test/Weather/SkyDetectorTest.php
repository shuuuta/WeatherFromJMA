<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\Sky;
use WeatherFromJMA\Weather\SkyDetector;

class SkyDetectorTest extends TestCase
{
  use LoadReportTrait;

  public function testDetectorFindData()
  {
    $weatherList = $this->LoadReport();
    $detector =  new SkyDetector();
    $timeline = $detector->getTImeline($weatherList);
    $this->assertSame(8, $timeline->count());
    $this->assertSame(3, count($timeline->getOverviews()));

    foreach ($timeline as $weather):
      $this->assertSame('くもり', $weather->value);
    endforeach;

    foreach ($timeline->getOverviews() as $weather):
      $this->assertMatchesRegularExpression('/くもり/', $weather->value);
    endforeach;
  }
}
