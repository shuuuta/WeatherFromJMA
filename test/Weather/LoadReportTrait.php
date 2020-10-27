<?php

namespace WeatherFromJMA\Test\Weather;

use ReflectionMethod;
use WeatherFromJMA\Report;
use WeatherFromJMA\WeatherCollector;

trait LoadReportTrait
{
  public function loadReport(): array
  {
    $xml = simplexml_load_file(__DIR__ . '/../sample/sample_03.xml');
    $report = new Report($xml);

    $collector = new WeatherCollector(['八丈島', '伊豆諸島南部']);
    $reflection = new ReflectionMethod($collector, 'loadReport');
    $reflection->setAccessible(true);
    $weatherList = $reflection->invoke($collector, $report);

    return $weatherList;
  }
}
