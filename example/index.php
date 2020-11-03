<?php

use WeatherFromJMA\Weathers;


require __DIR__ . '/../vendor/autoload.php';

echo PHP_EOL . 'Get weather data.' . PHP_EOL . PHP_EOL;

$weathers = new Weathers(['八丈島', '伊豆諸島南部']);

foreach ($weathers as $timeline) :
  $weatherType = $timeline->getType();
  echo $weatherType . PHP_EOL;
  foreach ($timeline as $weather) :
    switch ($weatherType):
      case 'rain':
        echo '  ' . $weather->getDate()->format('Ymd H:i') . ' : ' . $weather->condition . ' | ' . $weather->value . PHP_EOL;
        break;
      default:
        echo '  ' . $weather->getDate()->format('Ymd H:i') . ' : ' . $weather->value . PHP_EOL;
    endswitch;
  endforeach;
endforeach;

echo PHP_EOL . 'Overviews:' . PHP_EOL;
foreach ($weathers as $timeline) :
  echo $timeline->getType() . PHP_EOL;
  foreach ($timeline->getOverviews() as $weather) :
    echo '  ' . $weather->getDate()->format('Ymd H:i') . ' : ' . $weather->value . PHP_EOL;
  endforeach;
endforeach;
