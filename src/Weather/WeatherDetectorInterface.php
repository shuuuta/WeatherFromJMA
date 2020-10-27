<?php

namespace WeatherFromJMA\Weather;

use DateTime;

use SimpleXMLElement;

abstract class WeatherDetectorInterface
{
  protected array $timeId = [];

  //abstract protected function detectValues(SimpleXMLElement $property): array;
  //abstract protected function convertToWeather(array $weatherData): WeatherInterface;

  private $areaTypeTags = [
    '地点予報',
    '区域予報',
  ];
  private $areaTags = [
    '八丈島',
    '伊豆諸島南部',
  ];
  private $timeList = [];

  public function loadReport(SimpleXMLElement $xml): array
  {
    $weatherList = [];
    //$num = 0;

    //$mainContainers = $this->detectMainContainers($xml);

    //foreach ($mainContainers as $mainContainer) :
    //  $weathers = $this->getValues($mainContainer);
    //  $weatherList = array_merge($weatherList, $weathers);
    //endforeach;

    foreach ($xml->Body[0]->MeteorologicalInfos as $meteorologicalInfos) :
      foreach ($meteorologicalInfos->TimeSeriesInfo as $timeSeriesInfo) :
        $weathers = $this->getWeatherData($timeSeriesInfo);
        var_dump($weathers);
        //$num++;
        $weatherList = array_merge($weatherList, $weathers);
      endforeach;
    endforeach;

    //var_dump($num);
    return $weatherList;
  }

  //private function detectMainContainers(SimpleXMLElement $xml): SimpleXMLElement
  //{
  //  $xmlList = [];
  //  foreach ($xml->Body[0]->children() as $bodyChild) :
  //    if (in_array((string) $bodyChild['type'], $this->areaTypeTags)) :
  //      $xmlList[] = $bodyChild->TimeSeriesInfo->asXml();
  //    endif;
  //  endforeach;

  //  $joinedXmlString = '<Root xmlns:jmx_eb="http://xml.kishou.go.jp/jmaxml1/elementBasis1/">' . implode($xmlList) . '</Root>';
  //  $simpleXml = simplexml_load_string($joinedXmlString);
  //  $mainContainers = $simpleXml->children();

  //  return $mainContainers;
  //}

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

  private function getWeatherData(SimpleXMLElement $TimeSeriesInfo) :array
  {
    $weathers = [];

    $this->timeList = $this->getTimeList($TimeSeriesInfo);
    foreach ($TimeSeriesInfo->Item as $item) :
      if ($this->isArea($item)):
      //if (true):
      //   dev: Itemないに何のエリアがあるか確認
      //  foreach ($item->Area as $area):
      //    $areaName = $area->Name[0];
      //  endforeach;
      //  foreach ($item->Station as $station):
      //    $areaName = $station->Name[0];
      //  endforeach;
      //  echo $areaName . PHP_EOL;

        foreach ($item->Kind as $kind) :
          $property = $kind->Property[0];
          $weather = $this->detectValues($property);

          $weathers[] = $weather;
        endforeach;
      endif;
    endforeach;

    $this->timeList = [];

    return $weathers;
  }

  private function isArea(SimpleXMLElement $item): bool
  {
      $areaName;
      foreach ($item->Area as $area):
        $areaName = $area->Name[0];
      endforeach;
      foreach ($item->Station as $station):
        $areaName = $station->Name[0];
      endforeach;

      return in_array($areaName, $this->areaTags);
  }

  protected function getContainerDate(string $id): DateTime
  {
    return $this->timeList[$id];
  }
}
