<?php

/* @FILE: framework.php
   © Francis Dominic Fajardo - All Rights Reserved
   Unauthorized copying of this file, via any medium is strictly prohibited
   Proprietary and confidential
*/

class Framework
{
    public static $dir  = [];
    public static $com  = [];
    public static $rcom = [];

    public static function initialize()
    {
        $protocol = "http";
        $droot = $_SERVER["DOCUMENT_ROOT"];
        $sroot = $protocol . '://' . $_SERVER['HTTP_HOST'];

        // Directories
        self::$dir = [
            "ROOT"    => $droot,
            "COM"     => $droot . "/components",
            "DBP"     => $droot . "/components/db",
            "TPL"     => $droot . "/components/templates",
            "AST"     => $droot . "/assets",
            "CNT"     => $droot . "/assets/content",
            "IMG"     => $droot . "/assets/images",
            "S_ROOT"  => $sroot,
            "S_COM"   => $sroot . "/components",
            "S_DBP"   => $sroot . "/components/db",
            "S_TPL"   => $sroot . "/components/templates",
            "S_AST"   => $sroot . "/assets",
            "S_CNT"   => $sroot . "/assets/content",
            "S_IMG"   => $sroot . "/assets/images",
        ];

        // Components
        self::$com = [
            "CONFIG"  => self::$dir["COM"] . "/config.php",
            "TPLMAN"  => self::$dir["COM"] . "/template.php",
            "DB"      => self::$dir["DBP"] . "/database.php",
            "DBPAT"   => self::$dir["DBP"] . "/patients.php",
            "DBPRO"   => self::$dir["DBP"] . "/products.php",
            "DBUSR"   => self::$dir["DBP"] . "/users.php",
            "DBSIT"   => self::$dir["DBP"] . "/vaxsites.php",
            "DBVAX"   => self::$dir["DBP"] . "/vaxrecords.php",
            "DBWOR"   => self::$dir["DBP"] . "/workers.php",
            "DBLOC"   => self::$dir["DBP"] . "/locations.php",
        ];

        // Required components
        self::$rcom = [
            "CONFIG",
            "TPLMAN",
            "DB",
        ];

        // Load all required components
        foreach (self::$rcom as $component) {
            self::load($component);
        }

        // Set content type HTTP header
        header("Content-Type: text/html; charset=utf-8");
    }

    public static function load(string $name)
    {
        require(self::$com[$name]);
    }

    public static function getContent(string $name, string $extension = ".html")
    {
        $path = self::$dir["CNT"] . "/" . $name . $extension;
        return self::getFile($path);
    }

    public static function getTemplate(string $name)
    {
        $path = self::$dir["TPL"] . "/" . $name . ".html";
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
}

Framework::initialize();
