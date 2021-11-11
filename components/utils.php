<?php

class Utils
{
    public static function getContent(string $name, string $extension = ".html")
    {
        $path = Framework::$dir["CNT"] . "/" . $name . $extension;
        return self::getFile($path);
    }

    public static function getTemplate(string $name)
    {
        $path = Framework::$dir["TPL"] . "/" . $name . ".html";
        return self::getFile($path);
    }

    private static function getFile(string $path)
    {
        if (file_exists($path))
        {
            return file_get_contents($path);
        }
        else
        {
            throw new Exception("Missing file");
        }
    }

    public static function getOrdinal($number)
    {
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
    
    public static function redirect($page)
    {
        header('Location: ' . Framework::$dir["S_ROOT"] . "/" . $page);
        exit;
    }

    public static function getFullName($firstName, $middleName, $lastName, $suffix = null, $surnameFirst = true)
    {
        $suffix_spaced = empty($suffix) ? "" : " $suffix";
        
        if ($surnameFirst)
        {
            return trim(sprintf(
                "%s%s, %s %s",
                strtoupper($lastName),
                $suffix_spaced,
                $firstName,
                $middleName
            ));
        }

        return trim(sprintf(
            "%s %s %s%s",
            $firstName,
            $middleName,
            $lastName,
            $suffix_spaced
        ));
    }
}