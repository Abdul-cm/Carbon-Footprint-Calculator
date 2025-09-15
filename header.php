<?php
/**
 * Carbon Footprint Calculator - Header Template
 * 
 * This file contains the HTML header and navigation for all pages.
 * 
 * @version 2.0
 */

// Set timezone and include configuration
date_default_timezone_set('Europe/London');
require_once "config.php";

// Start secure session
startSecureSession();

echo <<<_END

	<!DOCTYPE html>
	<html lang="en">
    <head>
        <title>Carbon Footprint Calculator</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Calculate your carbon footprint with our comprehensive carbon emissions calculator">
        <meta name="keywords" content="carbon footprint, calculator, emissions, environment, sustainability">
        
        <!-- External libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        
        <!-- Local files -->
        <link rel="stylesheet" href="styles.css">
        <script src="script.js"></script>
	</head>

    <body>
    <div>
        <nav class="navbar navbar-custom navbar-dark navbar-expand-lg navbar-default">
            <div class="container-fluid">
                <a class="navbar-brand" href="home.php">
                    <strong>Carbon Calculator</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="nav navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" id="navText" href="home.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" id="navText" href="form_1details.php">Calculator</a></li>
                        <li class="nav-item"><a class="nav-link" id="navText" href="how.php">How We Calculate Your Footprint</a></li>    
                    </ul>
                </div>
            </div>
        </nav>    
        
_END;

?>