<?php

namespace WeatherFromJMA\Weather;

use DateTime;
use SimpleXMLElement;

class WaveDetector extends WeatherDetectorInterface
{
  private array $detectedData = [
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
        $waveHeightAttributes = $waveHeight->attributes();
        $condition = (string) $waveHeightAttributes->condition;
        $value = (string) $waveHeight;

        $this->detectedData['detail'][$timeId] = [
          'date' => $date,
          'condition' => $condition,
          'value' => $value,
        ];
        $this->detectedData['overview'][$timeId] = [
          'date' => $date,
          'sentence' => $sentence,
        ];
      endforeach;
    endif;
  }

  protected function outputWeather(): Wave
  {
    $wave = new Wave();

    foreach ($this->detectedData['overview'] as $overview) :
      $wave->addOverview($overview['sentence'], $overview['date']);
    endforeach;
    foreach ($this->detectedData['detail'] as $detail) :
      $wave->addDetail($detail);
    endforeach;

    return $wave;
  }
}
