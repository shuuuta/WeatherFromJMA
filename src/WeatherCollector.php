<?php

namespace WeatherFromJMA;

use DateTime;
use SimpleXMLElement;
use WeatherFromJMA\Weather\SkyDetector;
use WeatherFromJMA\Weather\WindDetector;

class WeatherCollector
{
  private $detectors = [
    SkyDetector::class,
  ];
  private $weathers = [];

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

  public function getWeathers(): array
  {
    return $this->weathers;
  }

  public function loadReports(ReportList $reportList): void
  {
    foreach ($reportList as $report) :
      $weatherList = $this->loadReport($report);
      $this->detectWeathers($weatherList);
    endforeach;
  }

  private function loadReport(Report $report): array
  {
    $weatherList = [];

    $xml = $report->raw;

    foreach ($xml->Body[0]->MeteorologicalInfos as $meteorologicalInfos) :
      foreach ($meteorologicalInfos->TimeSeriesInfo as $timeSeriesInfo) :
        $weatherData = $this->getPropertiesFromTimeSeriesInfo($timeSeriesInfo);
        $weatherList[] = $weatherData;
      endforeach;
    endforeach;

    return $weatherList;
  }

  private function detectWeathers(array $weatherData): void
  {
    foreach ($this->detectors as $detector) :
      $detector =  new $detector();
      $timeline = $detector->getTimeline($weatherData);

      if (!$timeline) :
        continue;
      endif;

      if (isset($this->weathers[$timeline->getType()])) :
        $this->weathers[$timeline->getType()] = $timeline->mergeTimeline($this->weathers[$timeline->getType()]);
      else :
        $this->weathers[$timeline->getType()] = $timeline;
      endif;
    endforeach;
  }

  private function getPropertiesFromTimeSeriesInfo(SimpleXMLElement $TimeSeriesInfo): array
  {
    $weatherData = [];

    foreach ($TimeSeriesInfo->Item as $item) :
      if ($this->isInArea($item)) :
        foreach ($item->Kind as $kind) :
          if (empty($weatherData)) :
            $weatherData['properties'] = [];
          endif;

          $properties = $kind->Property;
          foreach ($properties as $property) :
            $weatherData['properties'][] = $property;
          endforeach;
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

  private function isInArea(SimpleXMLElement $item): bool
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
