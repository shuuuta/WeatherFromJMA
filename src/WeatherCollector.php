<?php

namespace WeatherFromJMA;

use DateTime;
use SimpleXMLElement;
use WeatherFromJMA\Weather\SkyDetector;

class WeatherCollector
{
  private $detectors = [
    SkyDetector::class,
  ];

  private $areaTypeTags = [
    '地点予報',
    '区域予報',
  ];
  private $areaTags = [];

  public function __construct(array $areaTags)
  {
    foreach ($areaTags as $areaTag) :
      if (!is_string($areaTag)) :
        throw new InvalidArgumentException('WeatherController only accepts an array of string. Input array includes ' . $areaTag);
      endif;
      $this->areaTags[] = $areaTag;
    endforeach;
  }

  public function loadReports(ReportList $reportList): array
  {
    $weathers = [];

    foreach ($reportList as $report) :
      $weathers = array_merge($weathers, $this->loadReport($report));
    endforeach;

    return $weathers;
  }

  private function loadReport(Report $report): array
  {
    $weatherList = [];

    $xml = $report->raw;

    foreach ($xml->Body[0]->MeteorologicalInfos as $meteorologicalInfos) :
      foreach ($meteorologicalInfos->TimeSeriesInfo as $timeSeriesInfo) :
        $weatherData = $this->getWeatherData($timeSeriesInfo);
        $weatherList[] = $weatherData;
      endforeach;
    endforeach;

    return $this->getWeathers($weatherList);
  }

  private function getWeathers(array $weatherData): array
  {
    $weathers = [];

    foreach ($this->detectors as $detector) :
      $detector =  new $detector();
      $weathers[] = $detector->getWeather($weatherData);
    endforeach;

    return $weathers;
  }

  private function getWeatherData(SimpleXMLElement $TimeSeriesInfo): array
  {
    $weatherData = [];

    foreach ($TimeSeriesInfo->Item as $item) :
      if ($this->isArea($item)) :
        //dev: Itemないに何のエリアがあるか確認
        //if (true):
        //  foreach ($item->Area as $area):
        //    $areaName = $area->Name[0];
        //  endforeach;
        //  foreach ($item->Station as $station):
        //    $areaName = $station->Name[0];
        //  endforeach;
        //  echo $areaName . PHP_EOL;

        foreach ($item->Kind as $kind) :
          $property = $kind->Property[0];

          if (empty($weatherData)) :
            $weatherData['properties'] = [];
          endif;
          $weatherData['properties'][] = $property;
        endforeach;
      endif;
    endforeach;

    if (!empty($weatherData)) :
      $dateList = $this->getTimeList($TimeSeriesInfo);
      $weatherData['date'] = $dateList;
    endif;

    return $weatherData;
  }

  private function getTimeList(SimpleXMLElement $mainContainer): array
  {
    $timeList = [];

    foreach ($mainContainer->TimeDefines[0]->TimeDefine as $timeDefine) :
      $timeId = (string) $timeDefine['timeId'];
      $dateTime =  new DateTime($timeDefine->DateTime[0]);

      $timeList[$timeId] = $dateTime;
    endforeach;

    return $timeList;
  }

  private function isArea(SimpleXMLElement $item): bool
  {
    $areaName;
    foreach ($item->Area as $area) :
      $areaName = $area->Name[0];
    endforeach;
    foreach ($item->Station as $station) :
      $areaName = $station->Name[0];
    endforeach;

    return in_array($areaName, $this->areaTags);
  }
}
