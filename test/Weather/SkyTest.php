<?php

namespace WeatherFromJMA\Test\Weather;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Report;
use WeatherFromJMA\Weather\SkyDetector;

use WeatherFromJMA\Weather\WindDetector;

class SkyTest extends TestCase
{
  public function testLoadReport()
  {
    //$xml = simplexml_load_file(__DIR__ . '/../sample/sample_03.xml');

    //$detector = new SkyDetector();
    //echo PHP_EOL . 'test loadreport' . PHP_EOL;
    //$weathers = $detector->loadReport($xml);
    //echo 'end loadreport' . PHP_EOL;

    //$winddetector = new WindDetector();
    //$winddetector->loadReport($xml);
  }
}
