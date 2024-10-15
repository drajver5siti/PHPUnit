<?php

use App\TemperatureService;
use App\WeatherMonitor;
use PHPUnit\Framework\TestCase;

class WeatherMonitorTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testCorrectAverageIsReturned()
    {
        $mock = $this->createMock(TemperatureService::class);

        $map = [
            ['12:00', 20],
            ['14:00', 26],
        ];
        
        $mock
            ->expects($this->exactly(2))
            ->method('getTemperature')
            ->willReturnMap($map);

        $weather = new WeatherMonitor($mock);

        $start = "12:00";
        $end = "14:00";

        $avg = $weather->getAverageTemperature($start, $end);

        $this->assertEquals(23, $avg);
    }
    public function testCorrectAverageIsReturnedMockery()
    {
        $mock = Mockery::mock(TemperatureService::class);

        $mock
            ->shouldReceive('getTemperature')
            ->once()
            ->with('12:00')
            ->andReturn(20);


        $mock
            ->shouldReceive('getTemperature')
            ->once()
            ->with('14:00')
            ->andReturn(26);

        /** @var TemperatureService */
        $mock = $mock;

        $weather = new WeatherMonitor($mock);

        $start = "12:00";
        $end = "14:00";

        $avg = $weather->getAverageTemperature($start, $end);

        $this->assertEquals(23, $avg);
    }

}