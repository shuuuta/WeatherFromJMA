<?php

namespace WeatherFromJMA\Weather;

use DateTime;
use SimpleXMLElement;

class WindDetector extends WeatherDetectorInterface
{
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
        foreach ($property->WindDirectionPart->children('jmx_eb', true) as $windDirection) :
          $timeId = (string)  $windDirection->attributes()->refID;
          $date = $dateList[$timeId];
          $value = (string) $windDirection;

          $winds[$timeId] = [
            'date' => $date,
            'direction' => $value,
          ];
        endforeach;

        foreach ($property->WindSpeedPart->WindSpeedLevel as $windSpeed) :
          $attributes =  $windSpeed->attributes();
          $timeId = (string) $attributes->refID;
          $date = $dateList[$timeId];
          $description =  (string) $attributes->description;
          $value = (string) $windSpeed;

          $winds[$timeId] = array_merge( $winds[$timeId], [
            'date' => $date,
            'description' => $description,
            'speedLevel' => $value,
          ]);
        endforeach;

        //var_dump($winds);
        //foreach($winds as $windData) :
        //  $wind->addDetail($windData);
        //endforeach;
      endif;

      if ($title === $titlePattern['overview']) :
        foreach ($property->DetailForecast->WindForecastPart as $windPart) :
          $timeId =  (string) $windPart->attributes()->refID;
          foreach ($windPart->Sentence as $sentence):
            $date = $dateList[$timeId];
            $value = $sentence;

            //var_dump($value);
            //$wind->addOverview($value, $date);
          endforeach;
        endforeach;
      endif;



      //foreach ($property->WeatherPart->children('jmx_eb', true) as $child) :
      //foreach ($property->WeatherPart->children('jmx_eb', true) as $child) :
      //  $timeId =  $child->attributes()->refID;
      //  $date = $this->getContainerDate($timeId);
      //  $value = (string) $child;

      //  if ($title === $titlePattern['overview']) :
      //    $wind->addOverview($value, $date);
      //  elseif ($title === $titlePattern['overview']) :
      //    $wind->addDetail($value, $date);
      //  endif;
      //endforeach;
    endif;

    //return $wind;
  }

  protected function outputWeather(): Wind
  {
    $wind = new Wind();

    //foreach ($this->detectedData['overview'] as $overview) :
    //  $sky->addOverview($overview['value'], $overview['date']);
    //endforeach;
    //foreach ($this->detectedData['detail'] as $detail) :
    //  $sky->addDetail($detail['value'], $detail['date']);
    //endforeach;

    return $wind;
  }
}
