<?php

namespace WeatherFromJMA\Test;

use DateTime;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use WeatherFromJMA\ReportCollector;
use WeatherFromJMA\ReportList;

class ReportCollectorTest extends TestCase
{
  public function testSetArea()
  {
    $reportCollectotr = new ReportCollector();
    $testValue = 'test value';
    $reportCollectotr->setArea($testValue);

    $refrectionClass = new ReflectionClass(get_class($reportCollectotr));
    $property = $refrectionClass->getProperty('area');
    $property->setAccessible(true);

    $this->assertSame($testValue, $property->getValue($reportCollectotr));
  }

  public function testSetPoint()
  {
    $reportCollectotr = new ReportCollector();
    $testValue = 'test value';
    $reportCollectotr->setPoint($testValue);

    $refrectionClass = new ReflectionClass(get_class($reportCollectotr));
    $property = $refrectionClass->getProperty('point');
    $property->setAccessible(true);

    $this->assertSame($testValue, $property->getValue($reportCollectotr));
  }

  public function testSetDate()
  {
    $reportCollectotr = new ReportCollector();
    $testValue = '20201020';
    $reportCollectotr->setDate($testValue);

    $refrectionClass = new ReflectionClass(get_class($reportCollectotr));
    $property = $refrectionClass->getProperty('date');
    $property->setAccessible(true);

    $this->assertSame($testValue, $property->getValue($reportCollectotr)->format('Ymd'));
  }

  public function testDefaultDateIsToday()
  {
    $reportCollectotr = new ReportCollector();

    $refrectionClass = new ReflectionClass(get_class($reportCollectotr));
    $property = $refrectionClass->getProperty('date');
    $property->setAccessible(true);

    $this->assertSame(date('Ymd'), $property->getValue($reportCollectotr)->format('Ymd'));
  }

  public function testGetReportsReturnWeathers()
  {
    $reportCollectotr = new ReportCollector();

    $reportCollectotr->setDate('20201020');

    $result = $reportCollectotr->getReports();

    $this->assertInstanceOf('\\' . ReportList::class, $result);
  }

  public function testGetReportsThrowError()
  {
    $this->expectException(\RuntimeException::class);

    $reportCollectotr = new ReportCollector();

    $refrectionClass = new ReflectionClass(get_class($reportCollectotr));
    $property = $refrectionClass->getProperty('url');
    $property->setAccessible(true);
    $property->setValue($reportCollectotr, 'http://example.test');

    $reportCollectotr->getReports();
  }
}
