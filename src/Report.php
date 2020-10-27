<?php

namespace WeatherFromJMA;

use DateTime;
use SimpleXMLElement;

class Report
{
  private string $title;
  private DateTime $date;
  private SimpleXMLElement $raw;

  public function __construct(SimpleXMLElement $xml)
  {
    $this->loadXML($xml);
  }

  public function __get($key)
  {
    $whiteList = [
      'title',
      'date',
      'raw',
    ];
    if (in_array($key, $whiteList)) {
      return $this->$key;
    }
    return null;
  }

  protected function loadXML(SimpleXMLElement $xml)
  {
    $this->title = $xml->Control[0]->Title[0];
    $this->date = new DateTime($xml->Control[0]->DateTime[0]);
    $this->raw = $xml;
  }
}
