<?php

namespace App\Service;

use phpDocumentor\Reflection\Types\Integer;

class TimeConverter
{

    public function durationIntoMinutes($hours, $minutes):int
    {
        return ($hours * 60) + $minutes;
    }

    public function minutesIntoDuration($time):string
    {
        $hours = floor($time/60);
        $hoursString = $hours ? ($hours . ' h ') : '' ;

        $minutes = $time-($hours*60);
        $minutesString = $minutes ? ($minutes . ' minutes ') : '';

        return $hoursString . $minutesString;
    }

}
