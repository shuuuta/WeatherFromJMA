<?php

namespace WeatherFromJMA;

use ArrayIterator;
use IteratorAggregate;
use WeatherFromJMA\Weather\Timeline;

class Weathers implements IteratorAggregate
{
  protected array $weathers = [];

  public function __construct(array $areaTags, string $targetDate = '')
  {
    $reportCollector = new ReportCollector($targetDate);
    $reportList = $reportCollector->getReports();

    $weatherCollector = new WeatherCollector($areaTags);
    $weatherCollector->loadReports($reportList);
    $this->weathers = $weatherCollector->getWeathers();
  }

  public function __get($name): ?Timeline
  {
    if (isset($this->weathers[$name])) :
      return $this->weathers[$name];
    else :
      return null;
    endif;
  }

  public function getIterator(): ArrayIterator
  {
    return new ArrayIterator($this->weathers);
  }
}
