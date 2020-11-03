<?php

namespace WeatherFromJMA\Test;

use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\Timeline;
use WeatherFromJMA\Weathers;

class WeathersTest extends TestCase
{
  public function testWeathers()
  {
    $weathers = new Weathers(['八丈島', '伊豆諸島南部']);
    foreach ($weathers as $timeline) :
      $this->assertInstanceOf(Timeline::class, $timeline);
    endforeach;
  }
}
