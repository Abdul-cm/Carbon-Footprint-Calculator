<?php
/**
 * Carbon Footprint Calculator - Home Page
 * 
 * This is the main landing page for the Carbon Footprint Calculator.
 * It provides an introduction to the calculator and links to get started.
 * 
 * @version 2.0
 */

require_once "header.php";

echo <<<_END

<main role="main">
    <!-- Hero Section -->
    <div class="jumbotron bg-light py-5">
        <div class="container text-center">
            <h1 class="display-3 mb-4">Carbon Footprint Calculator</h1>
            <p class="lead">Measure your environmental impact and take steps towards a sustainable future</p>
            <p class="text-muted">Calculate your annual carbon footprint in just a few minutes</p>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container py-5">
        <div class="row text-center">
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 id="homeHeader" class="card-title">Calculate Your Impact</h2>
                        <p class="card-text">Climate change is happening now. Carbon emissions from your lifestyle choices, transportation and energy usage directly impact our planet. This is a global challenge, but you can be part of the solution.</p>
                        <p class="card-text">Use our comprehensive calculator to identify your emissions and start your journey toward building a better tomorrow.</p>
                        <a class="btn btn-success btn-lg" href="form_1details.php" role="button">Start Calculator &raquo;</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 id="homeHeader" class="card-title">Our Methodology</h2>
                        <p class="card-text">Our Carbon Footprint Calculator evaluates multiple aspects of your lifestyle:</p>
                        <ul class="list-unstyled text-start">
                            <li>• Personal Transportation</li>
                            <li>• Public Transport Usage</li>
                            <li>• Long Distance Flights</li>
                            <li>• Electric & Gas Consumption</li>
                        </ul>
                        <p class="card-text">Learn how we transform your data into accurate CO2e emissions estimates.</p>
                        <a class="btn btn-outline-secondary" href="how.php" target="_blank" role="button">Learn More &raquo;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="bg-light py-5">
        <div class="container text-center">
            <h3 class="mb-4">Why It Matters</h3>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 bg-transparent">
                        <div class="card-body">
                            <h4 class="text-success">6.4 tonnes</h4>
                            <p class="text-muted">Average UK carbon footprint per person</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 bg-transparent">
                        <div class="card-body">
                            <h4 class="text-warning">2 tonnes</h4>
                            <p class="text-muted">Target footprint for climate stability</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 bg-transparent">
                        <div class="card-body">
                            <h4 class="text-info">5 minutes</h4>
                            <p class="text-muted">Time to complete our calculator</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

_END;

require_once "footer.php";

?>