<?php

namespace WeatherFromJMA\Weather;

use DateTime;
use SimpleXMLElement;

class SkyDetector extends WeatherDetectorInterface
{
  protected string $weatherClass = Sky::Class;

  protected array $detectedData = [
    'overview' => [],
    'detail' => [],
  ];

  protected function detectValues(SimpleXMLElement $property, array $dateList): void
  {
    $titlePattern = [
      'overview' => '天気',
      'detail' => '３時間内卓越天気',
    ];

    $title = (string) $property->Type[0];
    if (in_array($title, $titlePattern)) :
      foreach ($property->WeatherPart->children('jmx_eb', true) as $child) :
        $timeId = (string) $child->attributes()->refID;
        $date = $dateList[$timeId];
        $value = (string) $child;

        if ($title === $titlePattern['overview']) :
          $this->detectedData['overview'][] = [
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
}
