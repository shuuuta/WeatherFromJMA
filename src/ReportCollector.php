<?php

namespace WeatherFromJMA;

use DateTime;
use RuntimeException;
use SimpleXMLElement;

class ReportCollector
{
  protected string $url = 'https://www.data.jma.go.jp/developer/xml/feed/regular_l.xml';

  protected DateTime $date;

  protected ReportList $reportList;

  public array $xmlData = [];

  public function __construct(string $targetDate = '')
  {
    $this->date = $targetDate ? $this->setDate($targetDate) : new DateTime();
    $this->reportList = new ReportList();
  }

  public function setDate(string $date): void
  {
    $this->date = new DateTime($date);
  }

  public function getReports(): ReportList
  {
    $xmlString = $this->curlGetContents($this->url);
    $baseXml = simplexml_load_string($xmlString);

    if (!$baseXml) {
      throw new RuntimeException('No Contents from target url.');
    }

    $this->getSubReports($baseXml);

    foreach ($this->xmlData as $xmlData) :
      $subXMLString = $this->curlGetContents($xmlData['link']);
      $this->reportList->addReport(simplexml_load_string($subXMLString));
    endforeach;

    return $this->reportList;
  }

  protected function curlGetContents(string $url): string
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result ?: '';
  }

  protected function getSubReports(SimpleXMLElement $baseXml): void
  {
    foreach ($baseXml->entry as $entry) :
      if (
        false === strpos($entry->content[0], '東京都府県天気予報')
        || false !== strpos($entry->title[0], '（Ｒ１）')
      ) :
        continue;
      endif;

      $updated = new DateTime($entry->updated[0]);
      if ($this->date->format('Ymd') !== $updated->format('Ymd')) :
        continue;
      endif;

      $count = count($this->xmlData);
      $this->xmlData[$count]['title'] = $entry->title[0];
      $this->xmlData[$count]['date'] = $updated;
      $this->xmlData[$count]['content'] = $entry->content[0];
      $this->xmlData[$count]['link'] = $entry->link->attributes()->href[0];
    endforeach;
  }
}
