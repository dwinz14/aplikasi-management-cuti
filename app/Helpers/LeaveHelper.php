<?php

namespace App\Helpers;

use Carbon\Carbon;

class LeaveHelper
{
    public static function calculateWorkingDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workingDays = 0;

        while ($start <= $end) {
            if (!in_array($start->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $workingDays++;
            }
            $start->addDay();
        }
        return $workingDays;
    }
}
