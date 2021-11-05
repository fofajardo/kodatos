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

        foreach (self::$registeredViews as $view) {
            $slug = $view::SLUG;

            if (is_array($slug))
            {
                foreach ($slug as $slug_part)
                {
                    if ($slug_part == $url_path)
                    {
                        $view->output();
                        break;
                    }
                }
                continue;
            }

            if ($slug == $url_path)
            {
                $view->output();
            }
        }
    }

    public static function register($view)
    {
        self::$registeredViews[] = $view;
    }
}

VWM::initialize();
