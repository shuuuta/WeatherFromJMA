<?php

namespace WeatherFromJMA\Weather;

use DateTime;
use SimpleXMLElement;

class SkyDetector extends WeatherDetectorInterface
{
  private array $detectedData = [
    'overview' => [],
    'detail' => [],
  ];

  public function getWeather(array $weatherData): Sky
  {
    foreach ($weatherData as $data) :
      foreach ($data['properties'] as $property) :
        $this->detectValues($property, $data['date']);
      endforeach;
    endforeach;

    return $this->outputWeather();
  }

  protected function detectValues(SimpleXMLElement $property, array $dateList): void
  {
    $titlePattern = [
      'overview' => '天気',
      'detail' => '３時間内卓越天気',
    ];

    // dev: Propertyに何のデータがあるかチェック
    //echo $property->Type[0] . PHP_EOL;

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

  protected function outputWeather(): Sky
  {
    $sky = new Sky();

    foreach ($this->detectedData['overview'] as $overview) :
      $sky->addOverview($overview['value'], $overview['date']);
    endforeach;
    foreach ($this->detectedData['detail'] as $detail) :
      $sky->addDetail($detail['value'], $detail['date']);
    endforeach;

    return $sky;
  }
}
