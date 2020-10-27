<?php

namespace WeatherFromJMA\Test;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Report;

class ReportTest extends TestCase
{
  public function testLoadXML()
  {
    $xml = simplexml_load_file(__DIR__ . '/sample/sample_01.xml');
    $report = new Report($xml);

    $this->assertSame('府県天気予報', $report->title);
    $this->assertSame('20201020', $report->date->format('Ymd'));
    $this->assertSame($xml, $report->raw);
  }
}
