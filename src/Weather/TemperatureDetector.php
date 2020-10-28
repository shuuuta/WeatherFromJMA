<?php

namespace WeatherFromJMA\Weather;

use SimpleXMLElement;

class TemperatureDetector extends WeatherDetectorInterface
{
  private array $detectedData = [
    'overview' => [],
    'detail' => [],
  ];

  protected function detectValues(SimpleXMLElement $property, array $dateList): void
  {
    $titlePattern = [
      'overviewDayMax' => '日中の最高気温',
      'overviewMax' => '最高気温',
      'overviewMorningMin' => '朝の最低気温',
      'overviewMin' => '最低気温',
      'detail' => '３時間毎気温',
    ];

    $title = (string) $property->Type[0];
    if (in_array($title, $titlePattern)) :
      foreach ($property->TemperaturePart->children('jmx_eb', true) as $child) :
        $timeId = (string) $child->attributes()->refID;
        $date = $dateList[$timeId];
        $value = (string) $child;

        if (
          $title === $titlePattern['overviewDayMax'] ||
          $title === $titlePattern['overviewMax']
        ) :
          $this->detectedData['overview'][] = [
            'type' => 'max',
            'name' => $title,
            'value' => $value,
            'date' => $date,
          ];
        elseif (
          $title === $titlePattern['overviewMin'] ||
          $title === $titlePattern['overviewMorningMin']
        ) :
          $this->detectedData['overview'][] = [
            'type' => 'min',
            'name' => $title,
            'value' => $value,
            'date' => $date,
          ];
        elseif ($title === $titlePattern['detail']) :
          $this->detectedData['detail'][] = [
            'value' => $value,
            'date' => $date,
          ];
        endif;
      endforeach;
    endif;
  }

  protected function outputWeather(): Temperature
  {
    $temperature = new Temperature();

    foreach ($this->detectedData['overview'] as $overview) :
      $temperature->addOverview($overview);
    endforeach;
    foreach ($this->detectedData['detail'] as $detail) :
      $temperature->addDetail($detail);
    endforeach;

    return $temperature;
  }
}
