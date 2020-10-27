<?php

namespace WeatherFromJMA\Test;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\ReportList;
use WeatherFromJMA\WeatherCollector;

class WeatherCollectorTest extends TestCase
{
  public function testLoadReports()
  {
    $xml = simplexml_load_file(__DIR__ . '/sample/sample_03.xml');
    $reportList = new ReportList();
    $reportList->addReport($xml);

    $weatherCollector = new WeatherCollector();
    $weatherCollector->loadReports($reportList);
  }
}

