<?php

namespace WeatherFromJMA;

use ArrayIterator;
use IteratorAggregate;

class Weathers implements IteratorAggregate
{
  protected array $weathers = [];

  public function __construct(array $areaTags, string $targetDate = '')
  {
    $reportCollector = new ReportCollector($targetDate);
    $reportList = $reportCollector->getReports();

    $weatherCollector = new WeatherCollector($areaTags);
    $this->weathers = $weatherCollector->loadReports($reportList);
  }

  public function getIterator(): ArrayIterator
  {
    return new ArrayIterator($this->weathers);
  }
}
