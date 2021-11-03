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
            <section id="section-view-header">
                <div id="primary-header-spacer"></div>
                <div class="content-layout">
                    <div id="pov-container">
                        <div id="pov-general">
                            <div class="title">GALLOWAY, Jane Joanne</div>
                            <div class="subtitle">Intramuros, Manila, Philippines</div>
                            <div class="subtitle">Date of Birth: June 12, 1898</div>
                        </div>
                        <div class="box v wp">
                            <div id="pov-qr-container">
                                <img src=" data:image/gif;base64,R0lGODlhZABkAJEAAAAAAP///wAAAAAAACH5BAEAAAIALAAAAABkAGQAAAL/jI+py+0Po5y02ouz3rz7D4biSJbmiabqCgLuC8fyDMH1jOcvpff0YwP6hi4ecXgrCo89I1OXBESfP0nQcg1kt7tD1imdfMVdA1dpLmvV4PblHE6j1/Oxlb2MV+AIvhd/NxfV1/QnmMcAh0N46KBYRac3uPAowyiJaJhz6aZQGcNJVlcoh+kIGHm5qNkZuVqaCBk7CsUqqucT6ilLqZZrG4hLmtprmVn6ShysCmnniqr7bAx7yzytLC18/em3rJn8aa09Dg5dTN7sm76tXkvtjbyOTgsKnN0d4Ty5Sz9rmm+O3799CfBFa0UQoT1n+o4BbPTwn0F7Ck9RKddv4cVk/1M2thsXzSMviyI/hvxYcuC5lCczxhPJoiPEmDSz1bxZcCROFhx35nSJreE7bP6KvjGpcWZSlUMPVlvqVGDUnxIDNsA3USrFc0Y9MJmKEWQeoRF/Nf2G9KxAsjI3bX0pVi1VtmOHMZQ3VVwrsizRsiOy12pfuNx0diW68qK4wtcI8i25GOUwDmbnKvZbVamGypEBYz7cYjLmr5+5juA8+ohe01jSBkVVObZkzVRbxrX81+3QniRtg7ULnB7d3a7vurtX3PCK4bVZN9/J/DfE6Ketgn6LmCZ1ucaZ4vZ4Xe5quGAGPz/vL9wt89K9h2Wesnv47+MjYnhMG7515xXR87132F9C/2kFzx77EXiVYAdmZ19qQMn2oDz6CYeXg7epR95ThCV3HFYSLsjYhcGJmNuE89QjXmnE5begbWG15RuAGiIHVHocutfiUi/WFSGLtB0G4Ynv6ZRVghTm1hmKNGZ2m2MjJhkikCDOpmSIUTpn4n0KerZhjTMGaOGVKfZ2lI9IWJiXjAXa2OGTZcYHJZXd8WYknGHKCZtoJJlHWpdM0skmeHceqWSSPh2KaKKKLspoo44+Cmmkkk4qaQEAOw==">
                            </div>
                            ade0e65274156f2f
                        </div>
                    </div>
                </div>
                <div class="content-layout">
                    <div class="box v">
                        <div class="card2 l">
                            <div class="title red">
                                <span class="cell hr">COVID-19 test result</span>
                                <span class="cell">
                                    <span class="mdi-set mdi-alert-circle"></span>
                                    <span>POSITIVE</span>
                                </span>
                            </div>
                            <div class="table">
                                <div class="table">
                                    <div class="row">
                                        <span class="cell hr">Date of Test Result:</span>
                                        <span class="cell">%TESTDATE%</span>
                                    </div>
                                    <div class="row">
                                        <span class="cell hr">Test Laboratory:</span>
                                        <span class="cell">%TESTSITE%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card2 l">
                            <div class="title green">
                                <span class="cell hr">COVID-19 vaccine</span>
                                <span class="cell">
                                    <span class="mdi-set mdi-checkbox-marked-circle"></span>
                                    <span>FULLY VACCINATED</span>
                                </span>
                            </div>
                            <div class="table">
                                <div class="row subhead">
                                    1st Dose
                                </div>
                                <div class="table">
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
                                <div class="row subhead">
                                    2nd Dose
                                </div>
                                <div class="table">
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
            </section>
        </main>
    </body>
</html>