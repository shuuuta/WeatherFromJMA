<?php

namespace WeatherFromJMA;

use ArrayIterator;
use IteratorAggregate;

class Weathers implements IteratorAggregate
{
  protected array $weathers = [];

  public function fetchWeathers(array $areaTags, string $targetDate = ''): array
  {
    $reportList = new ReportCollector($targetDate);

    $weatherCollector = new WeatherCollector($areaTags);
    $weathers = $weatherCollector->loadReeports($reportList);
    return $weathers;
  }

  public function getIterator(): ArrayIterator
  {
    return new ArrayIterator($this->weathers);
  }
}
