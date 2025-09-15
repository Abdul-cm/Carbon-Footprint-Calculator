<?php
/**
 * Carbon Footprint Calculator - Results Page
 * 
 * This page processes the final form submission and displays
 * the calculated carbon footprint results with comparisons.
 * 
 * @version 2.0
 */

require_once "header.php";

// Check if this is the final step with utilities data
if (!isset($_POST['gasUsageWeekly']) && !isset($_POST['gasUsageMonthly']) && !isset($_POST['gasUsageYearly'])) {
    header("Location: form_1details.php");
    exit();
}

// Initialize variables
$electricUsage = $gasUsage = $utilitiesSubtotal = $grandTotal = 0;
$errors = [];

// Carbon footprint multipliers (kg CO2e per kWh/unit)
$electricUsageMultiplier = 0.21233; // kg CO2e per kWh
$gasUsageMultiplier = 0.20297;      // kg CO2e per kWh

// Process electrical usage
if (isset($_POST['electricalUsageWeekly']) && is_numeric($_POST['electricalUsageWeekly']) && $_POST['electricalUsageWeekly'] >= 0) {
    $_SESSION['electricalUsageWeekly'] = floatval($_POST['electricalUsageWeekly']);
    $electricUsage = ($_SESSION['electricalUsageWeekly'] * 52) * $electricUsageMultiplier;
    $_SESSION['electricalUsageMonthly'] = $_SESSION['electricalUsageYearly'] = 0;
} elseif (isset($_POST['electricalUsageMonthly']) && is_numeric($_POST['electricalUsageMonthly']) && $_POST['electricalUsageMonthly'] >= 0) {
    $_SESSION['electricalUsageMonthly'] = floatval($_POST['electricalUsageMonthly']);
    $electricUsage = ($_SESSION['electricalUsageMonthly'] * 12) * $electricUsageMultiplier;
    $_SESSION['electricalUsageWeekly'] = $_SESSION['electricalUsageYearly'] = 0;
} elseif (isset($_POST['electricalUsageYearly']) && is_numeric($_POST['electricalUsageYearly']) && $_POST['electricalUsageYearly'] >= 0) {
    $_SESSION['electricalUsageYearly'] = floatval($_POST['electricalUsageYearly']);
    $electricUsage = $_SESSION['electricalUsageYearly'] * $electricUsageMultiplier;
    $_SESSION['electricalUsageWeekly'] = $_SESSION['electricalUsageMonthly'] = 0;
} else {
    $_SESSION['electricalUsageWeekly'] = $_SESSION['electricalUsageMonthly'] = $_SESSION['electricalUsageYearly'] = 0;
}

// Process gas usage
if (isset($_POST['gasUsageWeekly']) && is_numeric($_POST['gasUsageWeekly']) && $_POST['gasUsageWeekly'] >= 0) {
    $_SESSION['gasUsageWeekly'] = floatval($_POST['gasUsageWeekly']);
    $gasUsage = ($_SESSION['gasUsageWeekly'] * 52) * $gasUsageMultiplier;
    $_SESSION['gasUsageMonthly'] = $_SESSION['gasUsageYearly'] = 0;
} elseif (isset($_POST['gasUsageMonthly']) && is_numeric($_POST['gasUsageMonthly']) && $_POST['gasUsageMonthly'] >= 0) {
    $_SESSION['gasUsageMonthly'] = floatval($_POST['gasUsageMonthly']);
    $gasUsage = ($_SESSION['gasUsageMonthly'] * 12) * $gasUsageMultiplier;
    $_SESSION['gasUsageWeekly'] = $_SESSION['gasUsageYearly'] = 0;
} elseif (isset($_POST['gasUsageYearly']) && is_numeric($_POST['gasUsageYearly']) && $_POST['gasUsageYearly'] >= 0) {
    $_SESSION['gasUsageYearly'] = floatval($_POST['gasUsageYearly']);
    $gasUsage = $_SESSION['gasUsageYearly'] * $gasUsageMultiplier;
    $_SESSION['gasUsageWeekly'] = $_SESSION['gasUsageMonthly'] = 0;
} else {
    $_SESSION['gasUsageWeekly'] = $_SESSION['gasUsageMonthly'] = $_SESSION['gasUsageYearly'] = 0;
}

// Calculate utilities subtotal (convert from kg to tonnes)
$utilitiesSubtotal = ($electricUsage + $gasUsage) / 1000;
$_SESSION['utilitiessubtotal'] = $utilitiesSubtotal;

// Calculate grand total (add baseline 1.7 tonnes for other activities)
$grandTotal = ($_SESSION['personaltransportsubtotal'] ?? 0) + 
              ($_SESSION['publictransportsubtotal'] ?? 0) + 
              ($_SESSION['longdistancesubtotal'] ?? 0) + 
              $utilitiesSubtotal + 1.7;

$calculationDate = date('Y-m-d H:i:s');

// Database operations with prepared statements
$connection = getDatabaseConnection();
if (!$connection) {
    $errors[] = "Unable to save results. Please try again later.";
} else {
    try {
        // Insert calculation results
        $insertQuery = "INSERT INTO results (
            calculation_date, region_name, postcode, q_drive_vehicle, q_vehicle_type,
            q_vehicle_mileage_weekly, q_vehicle_mileage_monthly, q_vehicle_mileage_yearly,
            personal_transport_subtotal, q_travel_bus, q_bus_mileage_weekly,
            q_bus_mileage_monthly, q_bus_mileage_yearly, q_travel_tram,
            q_tram_lightrail_mileage_weekly, q_tram_lightrail_mileage_monthly,
            q_tram_lightrail_mileage_yearly, q_travel_london_underground,
            q_london_underground_mileage_weekly, q_london_underground_mileage_monthly,
            q_london_underground_mileage_yearly, q_travel_train, q_train_mileage_weekly,
            q_train_mileage_monthly, q_train_mileage_yearly, q_travel_coach,
            q_coach_mileage_weekly, q_coach_mileage_monthly, q_coach_mileage_yearly,
            public_transport_subtotal, q_domestic_flights, q_0_1500km_flights,
            q_1500_3000km_flights, q_3000_6000km_flights, q_6000_9000km_flights,
            q_9000km_plus_flights, long_distance_subtotal, q_home_electrical_usage_weekly,
            q_home_electrical_usage_monthly, q_home_electrical_usage_yearly,
            q_home_gas_usage_weekly, q_home_gas_usage_monthly, q_home_gas_usage_yearly,
            utilities_subtotal, grand_total_emissions
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $connection->prepare($insertQuery);
        if ($stmt) {
            $stmt->bind_param("sssssdddssddssddssddssddssddddddddsddsddsdd",
                $calculationDate,
                $_SESSION['region'] ?? '',
                $_SESSION['postcode'] ?? '',
                $_SESSION['driveQuestion'] ?? '',
                $_SESSION['vehicleType'] ?? '',
                $_SESSION['vehicleMilesWeekly'] ?? 0,
                $_SESSION['vehicleMilesMonthly'] ?? 0,
                $_SESSION['vehicleMilesYearly'] ?? 0,
                $_SESSION['personaltransportsubtotal'] ?? 0,
                $_SESSION['busQuestion'] ?? '',
                $_SESSION['busMilesWeekly'] ?? 0,
                $_SESSION['busMilesMonthly'] ?? 0,
                $_SESSION['busMilesYearly'] ?? 0,
                $_SESSION['tramQuestion'] ?? '',
                $_SESSION['tramMilesWeekly'] ?? 0,
                $_SESSION['tramMilesMonthly'] ?? 0,
                $_SESSION['tramMilesYearly'] ?? 0,
                $_SESSION['londonQuestion'] ?? '',
                $_SESSION['londonMilesWeekly'] ?? 0,
                $_SESSION['londonMilesMonthly'] ?? 0,
                $_SESSION['londonMilesYearly'] ?? 0,
                $_SESSION['trainQuestion'] ?? '',
                $_SESSION['trainMilesWeekly'] ?? 0,
                $_SESSION['trainMilesMonthly'] ?? 0,
                $_SESSION['trainMilesYearly'] ?? 0,
                $_SESSION['coachQuestion'] ?? '',
                $_SESSION['coachMilesWeekly'] ?? 0,
                $_SESSION['coachMilesMonthly'] ?? 0,
                $_SESSION['coachMilesYearly'] ?? 0,
                $_SESSION['publictransportsubtotal'] ?? 0,
                $_SESSION['flightsTotalDomestic'] ?? 0,
                $_SESSION['flightsTotalRed'] ?? 0,
                $_SESSION['flightsTotalYellow'] ?? 0,
                $_SESSION['flightsTotalGreen'] ?? 0,
                $_SESSION['flightsTotalBlue'] ?? 0,
                $_SESSION['flightsTotalPurple'] ?? 0,
                $_SESSION['longdistancesubtotal'] ?? 0,
                $_SESSION['electricalUsageWeekly'],
                $_SESSION['electricalUsageMonthly'],
                $_SESSION['electricalUsageYearly'],
                $_SESSION['gasUsageWeekly'],
                $_SESSION['gasUsageMonthly'],
                $_SESSION['gasUsageYearly'],
                $utilitiesSubtotal,
                $grandTotal
            );
            
            if (!$stmt->execute()) {
                error_log("Database insert error: " . $stmt->error);
                $errors[] = "Unable to save calculation results.";
            }
            $stmt->close();
        }
        
        // Get regional comparison data
        $regionQuery = "SELECT region_co2_estimate FROM regional_data WHERE region_name = ?";
        $stmt = $connection->prepare($regionQuery);
        if ($stmt) {
            $stmt->bind_param("s", $_SESSION['region']);
            $stmt->execute();
            $result = $stmt->get_result();
            $regionData = $result->fetch_assoc();
            $regionEstimate = $regionData ? floatval($regionData['region_co2_estimate']) : 6.4;
            $stmt->close();
        } else {
            $regionEstimate = 6.4; // Default UK average
        }
        
        // Get UK average
        $ukQuery = "SELECT region_co2_estimate FROM regional_data WHERE region_name = 'United Kingdom'";
        $result = $connection->query($ukQuery);
        $ukData = $result->fetch_assoc();
        $ukEstimate = $ukData ? floatval($ukData['region_co2_estimate']) : 6.4;
        
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        $errors[] = "An error occurred while processing your results.";
    }
    
    $connection->close();
}

// Calculate percentages and comparisons
$personalTransportSubtotal = round($_SESSION['personaltransportsubtotal'] ?? 0, 2);
$publicTransportSubtotal = round($_SESSION['publictransportsubtotal'] ?? 0, 2);
$longDistanceSubtotal = round($_SESSION['longdistancesubtotal'] ?? 0, 2);
$utilitiesSubtotal = round($utilitiesSubtotal, 2);
$grandTotal = round($grandTotal, 2);

$regionEstimate = round($regionEstimate, 2);
$ukEstimate = round($ukEstimate, 2);

$regionComparison = round((($grandTotal - $regionEstimate) / $regionEstimate) * 100, 2);
$ukComparison = round((($grandTotal - $ukEstimate) / $ukEstimate) * 100, 2);

$personalTransportPercent = $grandTotal > 0 ? round(($personalTransportSubtotal / $grandTotal) * 100, 2) : 0;
$publicTransportPercent = $grandTotal > 0 ? round(($publicTransportSubtotal / $grandTotal) * 100, 2) : 0;
$longDistancePercent = $grandTotal > 0 ? round(($longDistanceSubtotal / $grandTotal) * 100, 2) : 0;
$utilitiesPercent = $grandTotal > 0 ? round(($utilitiesSubtotal / $grandTotal) * 100, 2) : 0;

echo <<<_END
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Main Results -->
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-success text-white text-center">
                    <h2 class="mb-0">Your Carbon Footprint Results</h2>
                </div>
                <div class="card-body text-center py-5">
                    <h3 class="text-muted mb-2">Your estimated annual carbon footprint is</h3>
                    <h1 class="display-2 text-success mb-4">{$grandTotal} <small class="text-muted">tonnes CO2e</small></h1>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Regional Comparison</h5>
_END;

if ($regionComparison < 0) {
    $absRegionComparison = abs($regionComparison);
    echo <<<_END
                                    <p class="card-text text-success">
                                        <i class="fas fa-arrow-down"></i> 
                                        {$absRegionComparison}% below your regional average of {$regionEstimate} tonnes
                                    </p>
_END;
} else {
    echo <<<_END
                                    <p class="card-text text-warning">
                                        <i class="fas fa-arrow-up"></i> 
                                        {$regionComparison}% above your regional average of {$regionEstimate} tonnes
                                    </p>
_END;
}

if ($ukComparison < 0) {
    $absUkComparison = abs($ukComparison);
    echo <<<_END
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">UK Comparison</h5>
                                    <p class="card-text text-success">
                                        <i class="fas fa-arrow-down"></i> 
                                        {$absUkComparison}% below UK average of {$ukEstimate} tonnes
                                    </p>
_END;
} else {
    echo <<<_END
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">UK Comparison</h5>
                                    <p class="card-text text-warning">
                                        <i class="fas fa-arrow-up"></i> 
                                        {$ukComparison}% above UK average of {$ukEstimate} tonnes
                                    </p>
_END;
}

echo <<<_END
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Breakdown Chart -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Emissions Breakdown</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="breakdownChart" width="400" height="400"></canvas>
                        </div>
                        <div class="col-md-6">
                            <div class="breakdown-details">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span><i class="fas fa-car text-primary"></i> Personal Transport</span>
                                    <span><strong>{$personalTransportSubtotal} tonnes ({$personalTransportPercent}%)</strong></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span><i class="fas fa-bus text-success"></i> Public Transport</span>
                                    <span><strong>{$publicTransportSubtotal} tonnes ({$publicTransportPercent}%)</strong></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span><i class="fas fa-plane text-info"></i> Long Distance Travel</span>
                                    <span><strong>{$longDistanceSubtotal} tonnes ({$longDistancePercent}%)</strong></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span><i class="fas fa-home text-warning"></i> Home Energy</span>
                                    <span><strong>{$utilitiesSubtotal} tonnes ({$utilitiesPercent}%)</strong></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-shopping-cart text-secondary"></i> Other Activities</span>
                                    <span><strong>1.7 tonnes</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Recommendations -->
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Recommendations to Reduce Your Footprint</h4>
                </div>
                <div class="card-body">
                    <div class="row">
_END;

// Generate recommendations based on highest emissions
$recommendations = [];

if ($personalTransportSubtotal > 1.5) {
    $recommendations[] = [
        'icon' => 'fas fa-car',
        'title' => 'Reduce Vehicle Emissions',
        'text' => 'Consider carpooling, using public transport, cycling, or walking for shorter trips. If possible, switch to an electric or hybrid vehicle.'
    ];
}

if ($longDistanceSubtotal > 2.0) {
    $recommendations[] = [
        'icon' => 'fas fa-plane',
        'title' => 'Minimize Air Travel',
        'text' => 'Flying has a high carbon impact. Consider vacation destinations closer to home, or offset your flights when necessary.'
    ];
}

if ($utilitiesSubtotal > 1.0) {
    $recommendations[] = [
        'icon' => 'fas fa-home',
        'title' => 'Improve Home Energy Efficiency',
        'text' => 'Switch to renewable energy, improve insulation, use LED bulbs, and consider smart thermostats to reduce energy usage.'
    ];
}

// Add general recommendations if specific ones don't apply
if (empty($recommendations)) {
    $recommendations = [
        [
            'icon' => 'fas fa-leaf',
            'title' => 'Maintain Low Emissions',
            'text' => 'Great job! Continue your sustainable practices and consider encouraging others to reduce their carbon footprint.'
        ],
        [
            'icon' => 'fas fa-recycle',
            'title' => 'Focus on Circular Economy',
            'text' => 'Reduce waste by buying less, reusing items, and recycling properly. Choose products with minimal packaging.'
        ]
    ];
}

foreach ($recommendations as $index => $rec) {
    $colClass = count($recommendations) == 2 ? 'col-md-6' : 'col-md-4';
    echo <<<_END
                        <div class="{$colClass} mb-3">
                            <div class="h-100">
                                <i class="{$rec['icon']} fa-2x text-info mb-3"></i>
                                <h5>{$rec['title']}</h5>
                                <p class="text-muted">{$rec['text']}</p>
                            </div>
                        </div>
_END;
}

echo <<<_END
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="text-center">
                <a href="form_1details.php" class="btn btn-success btn-lg me-3">Calculate Again</a>
                <a href="how.php" class="btn btn-outline-info btn-lg">Learn More</a>
            </div>
        </div>
    </div>
</div>

<script>
// Chart.js configuration for breakdown chart
const ctx = document.getElementById('breakdownChart').getContext('2d');
const breakdownChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Personal Transport', 'Public Transport', 'Long Distance', 'Home Energy', 'Other Activities'],
        datasets: [{
            data: [{$personalTransportSubtotal}, {$publicTransportSubtotal}, {$longDistanceSubtotal}, {$utilitiesSubtotal}, 1.7],
            backgroundColor: [
                '#007bff',
                '#28a745', 
                '#17a2b8',
                '#ffc107',
                '#6c757d'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + ' tonnes (' + 
                               Math.round((context.parsed / {$grandTotal}) * 100) + '%)';
                    }
                }
            }
        }
    }
});
</script>
_END;

require_once "footer.php";

// Clear session data after displaying results
session_unset();
?>