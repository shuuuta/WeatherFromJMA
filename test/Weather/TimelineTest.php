<?php

namespace WeatherFromJMA\Test\Weather;

use ArgumentCountError;
use DateTime;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use WeatherFromJMA\Weather\Sky;
use WeatherFromJMA\Weather\Timeline;

class TimelineTest extends TestCase
{
  public function testConstructNeedsWeatherClassName()
  {
    try {
      $flag = false;
      $timeline = new Timeline('test class');
    } catch (InvalidArgumentException $e) {
      $flag = true;
      $this->assertSame('InvalidArgumentException', get_class($e));
    } finally {
      $this->assertTrue($flag, 'Argument class is not exist.');
    }

    try {
      $flag = false;
      $timeline = new Timeline(TimelineTest::class);
    } catch (InvalidArgumentException $e) {
      $flag = true;
      $this->assertSame('InvalidArgumentException', get_class($e));
    } finally {
      $this->assertTrue($flag, 'Argument class is not Weather.');
    }
  }

  public function testGetTypeReturnClassNameWithoutNamespace()
  {
    $timeLine = new TimeLine(Sky::class);
    $this->assertSame('Sky', $timeLine->getType());
  }

  public function testAddWeather()
  {
    $timeLine = new TimeLine(Sky::class);

    $samples = [
      [
        'date' => new DateTime('2020-10-30 11:00'),
        'value' => 'test value 11',
      ],
      [
        'date' => new DateTime('2020-10-30 12:00'),
        'value' => 'test value 12',
      ],
    ];

    foreach ($samples as $sample) :
      $timeLine->add($sample);
    endforeach;

    $this->assertSame(2, $timeLine->count());

    foreach ($timeLine as $weather) :
      $this->assertInstanceOf(Sky::class, $weather);
    endforeach;
  }

  public function testThrowInvalidArgumentWhenAddWeatherWithoutDate()
  {
    $timeLine = new TimeLine(Sky::class);
    try {
      $flag = false;
      $timeLine->add([
        'value' => 'without DateTime',
      ]);
    } catch (InvalidArgumentException $e) {
      $flag = true;
      $this->assertSame('InvalidArgumentException', get_class($e));
    } finally {
      $this->assertTrue($flag, 'Argument test failed.');
    }

    try {
      $flag = false;
      $timeLine->add([
        'date' => 'invalid Type',
        'value' => 'test value',
      ]);
    } catch (InvalidArgumentException $e) {
      $flag = true;
      $this->assertSame('InvalidArgumentException', get_class($e));
    } finally {
      $this->assertTrue($flag, 'Argument test failed.');
    }
  }

  public function testTimelineReturnWeathersSortedByDate()
  {
    $timeLine = new TimeLine(Sky::class);

    $timeOrder = [
      '2020-10-31',
      '2020-10-30',
      '2020-10-29',
    ];
    $samples = [
      [
        'date' => new DateTime($timeOrder[1]),
        'value' => 'test value 30',
      ],
      [
        'date' => new DateTime($timeOrder[0]),
        'value' => 'test value 31',
      ],
      [
        'date' => new DateTime($timeOrder[2]),
        'value' => 'test value 29',
      ],
    ];

    foreach ($samples as $sample) :
      $timeLine->add($sample);
    endforeach;

    foreach ($timeLine as $i => $weather) :
      $this->assertSame($timeOrder[$i], $weather->getDate()->format('Y-m-d'));
    endforeach;
  }

  public function testAddWeatherOnOverviews()
  {
    $timeLine = new TimeLine(Sky::class);

    $samples = [
      [
        'date' => new DateTime('2020-10-30 11:00'),
        'value' => 'test value 11',
      ],
      [
        'date' => new DateTime('2020-10-30 12:00'),
        'value' => 'test value 12',
      ],
    ];

    foreach ($samples as $sample) :
      $timeLine->addOverview($sample);
    endforeach;

    $overviews = $timeLine->getOverviews();
    $this->assertSame(2, count($overviews));

    foreach ($overviews as $weather) :
      $this->assertInstanceOf(Sky::class, $weather);
    endforeach;
  }

  public function testThrowInvalidArgumentWhenAddWeatherOnOverviewsWithoutDate()
  {
    $timeLine = new TimeLine(Sky::class);
    try {
      $flag = false;
      $timeLine->addOverview([
        'value' => 'without DateTime',
      ]);
    } catch (InvalidArgumentException $e) {
      $flag = true;
      $this->assertSame('InvalidArgumentException', get_class($e));
    } finally {
      $this->assertTrue($flag, 'Argument test failed.');
    }

    try {
      $flag = false;
      $timeLine->addOverview([
        'date' => 'invalid Type',
        'value' => 'test value',
      ]);
    } catch (InvalidArgumentException $e) {
      $flag = true;
      $this->assertSame('InvalidArgumentException', get_class($e));
    } finally {
      $this->assertTrue($flag, 'Argument test failed.');
    }
  }
}
