<?php

interface View
{
    public function getDocument();
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
            self::outputNotFound();
        }

        echo $view->output();
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
    
    public static function outputNotFound()
    {
        $view = self::findView("notfound");
        if (is_null($view))
        {
            echo "404 Not Found";
        }
        else
        {
            echo $view->output();
        }
        http_response_code(404);
        exit;
    }
}

if (!defined("SUPPRESS_VIEW"))
{
    VWM::initialize();
}
