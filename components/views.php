<?php

interface View
{
    public function output();
}

class VWM
{
    private static $registeredViews = [];

    public static function initialize()
    {
        // Load all view components
        foreach (glob(Framework::$dir["VWP"] . "/*.php") as $filename)
        {
            include_once $filename;
        }

        $url = parse_url($_SERVER["REQUEST_URI"]);
        $url_path = trim($url["path"], "/");

        $view = self::findView($url_path);

        if ($view == null)
        {
            $view = self::findView("notfound");
        }

        $view->output();
    }

    public static function findView($url_path)
    {
        foreach (self::$registeredViews as $view)
        {
            if (is_array($view::SLUG))
            {
                foreach ($view::SLUG as $slug_part)
                {
                    if ($slug_part == $url_path)
                    {
                        return $view;
                    }
                }
                continue;
            }

            if ($view::SLUG == $url_path)
            {
                return $view;
            }
        }

        return null;
    }

    public static function register($view)
    {
        self::$registeredViews[] = $view;
    }
}

VWM::initialize();
