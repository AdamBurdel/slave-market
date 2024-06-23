<?php

namespace SlaveMarket\Helper;

class DateTimeRoundHelper
{
    public static function roundHoursString(string $dateTime)
    {
        return date("Y-m-d H:00:00",strtotime($dateTime));
    }
}