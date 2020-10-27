<?php

namespace WeatherFromJMA\Weather;

use DateTime;
use SimpleXMLElement;

class WindDetector extends WeatherDetectorInterface
{
  private array $detectedData = [
    'overview' => [],
    'detail' => [],
  ];

  public function getWeather(array $weatherData): Wind
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
      'overview' => '風',
      'detail' => '３時間内代表風',
    ];

    $title = (string) $property->Type[0];
    if (in_array($title, $titlePattern)) :

      $winds = [];

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
            'speedLevel' => $value,
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
              'sentence' => $sentence,
            ];
          endforeach;
        endforeach;
      endif;
    endif;
  }

  protected function outputWeather(): Wind
  {
    $wind = new Wind();

    foreach ($this->detectedData['overview'] as $overview) :
      $wind->addOverview($overview['sentence'], $overview['date']);
    endforeach;
    foreach ($this->detectedData['detail'] as $detail) :
      $wind->addDetail($detail);
    endforeach;

    return $wind;
  }
}
