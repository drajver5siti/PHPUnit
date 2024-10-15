<?php

namespace App;

class WeatherMonitor
{
    public function __construct(private readonly TemperatureService $service)
    {
        
    }

    public function getAverageTemperature(string $start, string $end): int
    {
        $startTemp = $this->service->getTemperature($start);
        $endTemp = $this->service->getTemperature($end);

        return ($startTemp + $endTemp) / 2;
    }
}