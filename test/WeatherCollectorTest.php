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

    $weatherCollector = new WeatherCollector(['八丈島', '伊豆諸島南部']);
    $weatherCollector->loadReports($reportList);
  }
}

