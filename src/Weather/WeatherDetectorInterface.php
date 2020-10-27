<?php

namespace WeatherFromJMA\Weather;

use DateTime;

use SimpleXMLElement;

abstract class WeatherDetectorInterface
{
  abstract protected function detectValues(SimpleXMLElement $property, array $dateList): void;
  abstract protected function outputWeather(): WeatherInterface;

  public function getWeather(array $weatherData): WeatherInterface
  {
    foreach ($weatherData as $data) :
      foreach ($data['properties'] as $property) :
        $this->detectValues($property, $data['date']);
      endforeach;
    endforeach;

    return $this->outputWeather();
  }
}
