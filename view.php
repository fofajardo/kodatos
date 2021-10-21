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
            <div id="primary-header-spacer"></div>
            <section id="section-view-header" class="bg ib0">
                <div class="content-layout">
                    <div id="pov-container">
                        <div id="pov-general">
                            <div class="title">DOE, Jane Galloway</div>
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
                                    <span>%VAXDATE%</span>
                                </div>
                                <div class="status-date-box">
                                    <span>
                                        <span class="mdi-set mdi-checkbox-marked-circle"></span>
                                        Last tested on:
                                    </span>
                                    <span>%TESTDATE%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="section-view-body" class="bg ib0 vh-full">
                <div>
                </div>
            </section>
        </main>
    </body>
</html>