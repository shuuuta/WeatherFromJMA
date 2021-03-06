<?php

namespace WeatherFromJMA\Weather;

use DateTime;
use SimpleXMLElement;

class WindDetector extends WeatherDetectorInterface
{
  protected string $weatherClass = Wind::Class;

  protected array $detectedData = [
    'overview' => [],
    'detail' => [],
  ];

  protected function detectValues(SimpleXMLElement $property, array $dateList): void
  {
    $titlePattern = [
      'overview' => '風',
      'detail' => '３時間内代表風',
    ];

    $title = (string) $property->Type[0];
    if (in_array($title, $titlePattern)) :

      if ($title === $titlePattern['detail']) :
        foreach ($property->WindSpeedPart->WindSpeedLevel as $windSpeed) :
          $attributes =  $windSpeed->attributes();
          $timeId = (string) $attributes->refID;
          $date = $dateList[$timeId];
          $description =  (string) $attributes->description;
          $value = (string) $windSpeed;

          $this->detectedData['detail'][$timeId] = [
            'date' => $date,
            'description' => $description,
            'value' => $value,
          ];
        endforeach;

        foreach ($property->WindDirectionPart->children('jmx_eb', true) as $windDirection) :
          $timeId = (string)  $windDirection->attributes()->refID;
          $date = $dateList[$timeId];
          $value = (string) $windDirection;

          $this->detectedData['detail'][$timeId]['direction'] = $value;
        endforeach;
      endif;

      if ($title === $titlePattern['overview']) :
        foreach ($property->DetailForecast->WindForecastPart as $windPart) :
          $timeId =  (string) $windPart->attributes()->refID;
          foreach ($windPart->Sentence as $sentence) :
            $date = $dateList[$timeId];

            $this->detectedData['overview'][$timeId] = [
              'date' => $date,
              'value' => $sentence,
            ];
          endforeach;
        endforeach;
      endif;
    endif;
  }
}
