<?php

namespace WeatherFromJMA\Weather;

use DateTime;

use SimpleXMLElement;

abstract class WeatherDetectorInterface
{
  protected string $weatherClass;
  abstract protected function detectValues(SimpleXMLElement $property, array $dateList): void;

  public function getTimeline(array $weatherData): Timeline
  {
    foreach ($weatherData as $data) :
      foreach ($data['properties'] as $property) :
        $this->detectValues($property, $data['date']);
      endforeach;
    endforeach;

    return $this->outputWeather();
  }

  protected function outputWeather(): Timeline
  {
    $timeline = new Timeline($this->weatherClass);

    foreach ($this->detectedData['overview'] as $overview) :
      $timeline->addOverview($overview);
    endforeach;
    foreach ($this->detectedData['detail'] as $detail) :
      $timeline->add($detail);
    endforeach;

    return $timeline;
  }
}
