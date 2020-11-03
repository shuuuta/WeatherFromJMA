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
    $timeline = new Timeline(Sky::class);
    $this->assertSame('sky', $timeline->getType());
  }

  public function testAddWeather()
  {
    $timeline = new Timeline(Sky::class);

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
      $timeline->add($sample);
    endforeach;

    $this->assertSame(2, $timeline->count());

    foreach ($timeline as $weather) :
      $this->assertInstanceOf(Sky::class, $weather);
    endforeach;
  }

  public function testThrowInvalidArgumentWhenAddWeatherWithoutDate()
  {
    $timeline = new Timeline(Sky::class);
    try {
      $flag = false;
      $timeline->add([
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
      $timeline->add([
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
    $timeline = new Timeline(Sky::class);

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
      $timeline->add($sample);
    endforeach;

    foreach ($timeline as $i => $weather) :
      $this->assertSame($timeOrder[$i], $weather->getDate()->format('Y-m-d'));
    endforeach;
  }

  public function testAddWeatherOnOverviews()
  {
    $timeline = new Timeline(Sky::class);

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
      $timeline->addOverview($sample);
    endforeach;

    $overviews = $timeline->getOverviews();
    $this->assertSame(2, count($overviews));

    foreach ($overviews as $weather) :
      $this->assertInstanceOf(Sky::class, $weather);
    endforeach;
  }

  public function testThrowInvalidArgumentWhenAddWeatherOnOverviewsWithoutDate()
  {
    $timeline = new Timeline(Sky::class);
    try {
      $flag = false;
      $timeline->addOverview([
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
      $timeline->addOverview([
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

  public function testMergeTimeline()
  {
    $timeline = new Timeline(Sky::class);
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
      $timeline->addOverview($sample);
      $timeline->add($sample);
    endforeach;

    $timeline2 = new Timeline(Sky::class);
    $samples2 = [
      [
        'date' => new DateTime('2020-10-30 11:00'),
        'value' => 'test value 11',
      ],
      [
        'date' => new DateTime('2020-10-30 12:00'),
        'value' => 'test value 12',
      ],
    ];
    foreach ($samples2 as $sample) :
      $timeline2->addOverview($sample);
      $timeline2->add($sample);
    endforeach;

    $timeline->mergeTimeline($timeline2);

    $this->assertSame(4, $timeline->count());
    $overviews = $timeline->getOverviews();
    $this->assertSame(4, count($overviews));
  }
}
