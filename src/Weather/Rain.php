<?php

namespace WeatherFromJMA\Weather;

class Rain extends WeatherInterface
{
  private string $condition;

  public function setOverview(array $data): void
  {
    if (isset($data['condition']) && isset($data['value'])) :
      $this->value = $data['condition'] . 'の確率' . $data['value'] . '％';
    else :
      $this->value = '';
    endif;

    $this->condition = $data['condition'] ?? '';
  }

  public function setDetail(array $data): void
  {
    $this->value = $data['value'] ?? '';
    $this->condition = $data['condition'] ?? '';
  }

  public function __get(string $name)
  {
    switch ($name):
      case 'value':
        return $this->value;
        break;
      case 'condition':
        return $this->condition;
        break;
      default:
        return null;
    endswitch;
  }
}
