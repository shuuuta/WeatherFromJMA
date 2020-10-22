<?php

namespace WeatherFromJMA;

use ArrayIterator;
use IteratorAggregate;

abstract class WeathersInterface implements IteratorAggregate
{
  protected array $weathers = [];

  public function getIterator(): ArrayIterator
  {
    return new ArrayIterator($this->weathers);
  }
}
