<?php

namespace WeatherFromJMA\Weather;

use DateTime;
use SimpleXMLElement;

class WaveDetector extends WeatherDetectorInterface
{
  protected string $weatherClass = Wave::Class;

  protected array $detectedData = [
    'overview' => [],
    'detail' => [],
  ];

  protected function detectValues(SimpleXMLElement $property, array $dateList): void
  {
    $title = (string) $property->Type[0];

    if ('波' === $title) :
      foreach ($property->DetailForecast->WaveHeightForecastPart as $part) :
        $attributes = $part->attributes();
        $timeId = (string) $attributes->refID;
        $date = $dateList[$timeId];

        $sentence = $part->Sentence;

        $waveHeight = $part->Base->children('jmx_eb', true);
        $value = (string) $waveHeight;

        $this->detectedData['detail'][$timeId] = [
          'date' => $date,
          'value' => $value,
        ];
        $this->detectedData['overview'][$timeId] = [
          'date' => $date,
          'value' => $sentence,
        ];
      endforeach;
    endif;
  }
}
