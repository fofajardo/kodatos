<?php

class Utils
{
    public static function getOrdinal($number) {
        $ends = ["th", "st", "nd", "rd", "th", "th", "th", "th", "th", "th"];
        if ((($number % 100) >= 11) && (($number%100) <= 13))
        {
            return $number . "th";
        }
        else
        {
            return $number . $ends[$number % 10];
        }
    }
}