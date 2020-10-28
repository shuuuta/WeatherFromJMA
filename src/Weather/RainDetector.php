<?php

namespace WeatherFromJMA\Weather;

use SimpleXMLElement;

class RainDetector extends WeatherDetectorInterface
{
  private array $detectedData = [
    'detail' => [],
  ];

  protected function detectValues(SimpleXMLElement $property, array $dateList): void
  {
    $title = (string) $property->Type[0];

    if ('降水確率' === $title) :
      foreach ($property->ProbabilityOfPrecipitationPart->children('jmx_eb', true) as $precipitation) :
        $attributes = $precipitation->attributes();
        $timeId = (string) $attributes->refID;
        $date = $dateList[$timeId];

        $condition = (string) $attributes->condition;

        $value = (string) $precipitation;

        $this->detectedData['detail'][$timeId] = [
          'date' => $date,
          'condition' => $condition,
          'value' => $value,
        ];
      endforeach;
    endif;
  }

  protected function outputWeather(): Rain
  {
    $rain = new Rain();

    foreach ($this->detectedData['detail'] as $detail) :
      $rain->addDetail($detail);
    endforeach;

    return $rain;
  }
}
