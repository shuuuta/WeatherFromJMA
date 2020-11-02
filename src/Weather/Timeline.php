<?php

namespace WeatherFromJMA\Weather;

use ArrayIterator;
use Generator;
use InvalidArgumentException;
use IteratorAggregate;

class Timeline implements IteratorAggregate
{
  private string $class;
  private array $weathers = [];
  private array $overviews = [];

  public function __construct(string $className)
  {
    $this->checkWeatherClassName($className);
    $this->class = $className;
  }

  private function checkWeatherClassName(string $className): bool
  {
    if (!class_exists($className)) :
      throw new InvalidArgumentException('Input class is not exist. Input is ' . $className . '.');
    endif;

    $class = new $className();
    if (!$class instanceof WeatherInterface) :
      throw new InvalidArgumentException('Input class is not instance of WeatherInterface. Input is ' . $className . '.');
    endif;

    return true;
  }

  public function getType(): string
  {
    $fullClassName = $this->class;
    $type = preg_replace('/^.+\\\([^\\\]+)$/', '$1', $fullClassName);
    return $type;
  }

  public function add(array $data): void
  {
    $this->checkData($data);
    $this->weathers[] = new $this->class($data);
  }

  public function addOverview(array $data): void
  {
    $this->checkData($data);
    $this->overviews[] = new $this->class($data, 'overview');
  }


  public function getOverviews(): array
  {
    return $this->overviews;
  }

  private function checkData(array $data): bool
  {
    if (!isset($data['date'])) :
      throw new InvalidArgumentException('Input date does not exist.');
    elseif ('object' !== gettype($data['date']) || 'DateTime' !== get_class($data['date'])) :
      throw new InvalidArgumentException('Input date is not DateTime. Input is ' . gettype($data['date']) . '.');
    endif;

    return true;
  }

  public function count(): int
  {
    return count($this->weathers);
  }

  public function getIterator(): Generator
  {
    $this->sortByDate($this->weathers);
    foreach ($this->weathers as $key => $val) {
      yield $key => $val;
    }
  }

  private function sortByDate(array &$array): void
  {
    usort($array, function ($first, $second) {
      return $first->getDate() < $second->getDate();
    });
  }
}
