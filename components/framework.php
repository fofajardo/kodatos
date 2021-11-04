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
            "EXT"     => $droot . "/components/external",
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
            "UTILS"   => self::$dir["COM"] . "/utils.php",
            "TPLMAN"  => self::$dir["COM"] . "/template.php",
            "AUTH"    => self::$dir["COM"] . "/auth.php",
            // Database
            "DB"      => self::$dir["DBP"] . "/database.php",
            "DBPAT"   => self::$dir["DBP"] . "/patients.php",
            "DBPRO"   => self::$dir["DBP"] . "/products.php",
            "DBACC"   => self::$dir["DBP"] . "/accounts.php",
            "DBSES"   => self::$dir["DBP"] . "/sessions.php",
            "DBSIT"   => self::$dir["DBP"] . "/vaxsites.php",
            "DBVAX"   => self::$dir["DBP"] . "/vaxrecords.php",
            "DBWOR"   => self::$dir["DBP"] . "/workers.php",
            "DBLOC"   => self::$dir["DBP"] . "/locations.php",
            "DBTSR"   => self::$dir["DBP"] . "/testrecords.php",
            "DBTTY"   => self::$dir["DBP"] . "/testtype.php",
        ];

        // Required components
        self::$rcom = [
            "CONFIG",
            "UTILS",
            "TPLMAN",
            "DB",
            "AUTH"
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
        require_once(self::$com[$name]);
    }

    public static function loadMultiple(array $names)
    {
        foreach ($names as $component) {
            self::load($component);
        }
    }
}

Framework::initialize();
