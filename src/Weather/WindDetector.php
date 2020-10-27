<?php

namespace WeatherFromJMA\Weather;

use DateTime;
use SimpleXMLElement;

class WindDetector extends WeatherDetectorInterface
{
  protected function detectRule(SimpleXMLElement $property): Wind
  {
    $titlePattern = [
      'overview' => '風',
      'detail' => '３時間内代表風',
    ];

    $wind = new Wind();

    $title = (string) $property->Type[0];

    if (in_array($title, $titlePattern)) :

      $winds = [];

      if ($title === $titlePattern['detail']) :
        foreach ($property->WindDirectionPart->children('jmx_eb', true) as $windDirection) :
          $timeId = (string)  $windDirection->attributes()->refID;
          $date = $this->getContainerDate($timeId);
          $value = (string) $windDirection;

          $winds[$timeId] = [
            'date' => $date,
            'direction' => $value,
          ];
        endforeach;

        foreach ($property->WindSpeedPart->WindSpeedLevel as $windSpeed) :
          $attributes =  $windSpeed->attributes();
          $timeId = (string) $attributes->refID;
          $date = $this->getContainerDate($timeId);
          $description =  (string) $attributes->description;
          $value = (string) $windSpeed;

          $winds[$timeId] = array_merge( $winds[$timeId], [
            'date' => $date,
            'description' => $description,
            'speedLevel' => $value,
          ]);
        endforeach;

        foreach($winds as $windData) :
          $wind->addDetail($windData);
        endforeach;
      endif;

      if ($title === $titlePattern['overview']) :
        foreach ($property->DetailForecast->WindForecastPart as $windPart) :
          $timeId =  $windPart->attributes()->refID;
          foreach ($windPart->Sentence as $sentence):
            $date = $this->getContainerDate($timeId);
            $value = $sentence;

            $wind->addOverview($value, $date);
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

    return $wind;
  }
}
