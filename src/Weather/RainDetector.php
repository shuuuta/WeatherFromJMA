<?php

namespace WeatherFromJMA\Weather;

use SimpleXMLElement;

class RainDetector extends WeatherDetectorInterface
{
  protected string $weatherClass = Rain::Class;
  protected array $detectedData = [
    'overview' => [],
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

        $values = [
          'date' => $date,
          'condition' => $condition,
          'value' => $value,
        ];
        $this->detectedData['overview'][] = $values;
        $this->detectedData['detail'][] = $values;
      endforeach;
    endif;
  }
}
