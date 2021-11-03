<?php

require_once "./components/framework.php";

// new Template();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View - KoDatos</title>
        <link rel="shortcut icon" href="favicon.ico">
        <link href="assets/common.css" rel="stylesheet">
        <!-- <script src="assets/common.js" type="text/javascript"></script> -->
    </head>
    <body>
        <header id="primary-header">
            <nav id="left-nav">
                <ul>
                    <li>
                        <a class="link" href="/">
                            <span style="visibility:collapse;font-size:0;">KoDatos</span>
                            <img src="assets/images/logo.svg#sprite-inv"/>
                        </a>
                    </li>
                </ul>
            </nav>
            <nav id="right-nav">
                <ul>
                    <li>
                        <a class="link" href="/sign-in">
                            <span class="mdi-set mdi-account"></span>
                        </a>
                    </li>
                </ul>
            </nav>
        </header>
        <!-- <main id="main" class="bg ib0 vh-full"> -->
        <main id="main">
            <section id="section-view-header" class="bg ib0 vh-full">
                <div id="primary-header-spacer"></div>
                <div class="content-layout">
                    <div id="pov-container">
                        <div id="pov-general">
                            <div class="title">GALLOWAY, Jane Joanne</div>
                            <div class="subtitle">Intramuros, Manila, Philippines</div>
                            <div class="subtitle">Date of Birth: June 12, 1898</div>
                        </div>
                        <div class="box v wp">
                            <div id="pov-status-container">
                                    <div class="status-box red">
                                        <span class="mdi-set mdi-alert-circle"></span>
                                        <span>COVID-19 POSITIVE</span>
                                    </div>
                                    <div class="status-box green">
                                        <span class="mdi-set mdi-checkbox-marked-circle"></span>
                                        <span>FULLY VACCINATED</span>
                                    </div>
                            </div>
                            <div id="pov-dates-container">
                                <div class="status-date-box">
                                    <span>
                                        <span class="mdi-set mdi-checkbox-marked-circle"></span>
                                        Last vaccinated on:
                                    </span>
                                    <span>%RECENTVAXDATE%</span>
                                </div>
                                <div class="status-date-box">
                                    <span>
                                        <span class="mdi-set mdi-checkbox-marked-circle"></span>
                                        Last tested on:
                                    </span>
                                    <span>%RECENTTESTDATE%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-layout">
                    <div class="box v">
                        <div class="card l">
                            <div class="table">
                                <div class="title">
                                    <span class="cell hr">COVID-19 vaccine</span>
                                    <span class="cell">
                                        <span class="mdi-set mdi-checkbox-marked-circle"></span>
                                        <span>FULLY VACCINATED</span>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="cell table">
                                        <div class="row">
                                            <span class="cell hr">Date of Vaccination:</span>
                                            <span class="cell">%VAXDATE%</span>
                                        </div>
                                        <div class="row">
                                            <span class="cell hr">Vaccine/Prophylaxis:</span>
                                            <span class="cell">%VAXTYPE%</span>
                                        </div>
                                        <div class="row">
                                            <span class="cell hr">Product Name/Manufacturer:</span>
                                            <span class="cell">%PRODNAME%</span>
                                        </div>
                                        <div class="row">
                                            <span class="cell hr">Lot Number:</span>
                                            <span class="cell">%LOTNUM%</span>
                                        </div>
                                        <div class="row">
                                            <span class="cell hr">Vaccination Site:</span>
                                            <span class="cell">%VAXSITE%</span>
                                        </div>
                                        <div class="row">
                                            <span class="cell hr">Healthcare Professional:</span>
                                            <span class="cell">%HCWNAME%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>