<?php

namespace WeatherFromJMA;

use ArrayIterator;
use IteratorAggregate;
use SimpleXMLElement;

class ReportList implements IteratorAggregate
{
  protected $reports = [];

  public function __construct(array $reports = [])
  {
    foreach ($reports as $report) {
      $this->addReport($report);
    }
  }

  public function addReport(SimpleXMLElement $report): void
  {
    $this->reports[] = $report;
  }

  public function getIterator(): ArrayIterator
  {
    return new ArrayIterator($this->reports);
  }
}
