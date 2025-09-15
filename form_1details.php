<?php
/**
 * Carbon Footprint Calculator - Step 1: Personal Details
 * 
 * This form collects basic location information from the user.
 * 
 * @author Abdul-cm
 * @version 2.0
 */

require_once "header.php";

// Initialize error messages
$errors = [];

// Process form submission if POST data exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $region = sanitizeInput($_POST['region'] ?? '');
    $postcode = sanitizeInput($_POST['postcode'] ?? '');
    
    // Validate inputs
    if (empty($region) || !validateRegion($region)) {
        $errors[] = "Please select a valid UK region.";
    }
    
    if (empty($postcode) || !validatePostcode($postcode)) {
        $errors[] = "Please enter a valid UK postcode.";
    }
    
    // If no errors, proceed to next step
    if (empty($errors)) {
        $_SESSION['region'] = $region;
        $_SESSION['postcode'] = strtoupper(str_replace(' ', '', $postcode));
        header("Location: form_2personaltransport.php");
        exit();
    }
}

echo<<<_END
    <br>
    <div class="container">
        <div class="progress mb-4">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 17%;" aria-valuenow="17" aria-valuemin="0" aria-valuemax="100">Step 1 of 5</div>
        </div>
    </div>
    
    <div class="container" id="form">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4" id="formHeader">Your Location Details</h2>
                        <p class="text-center text-muted mb-4">Help us provide accurate regional carbon footprint comparisons</p>
_END;

// Display errors if any
if (!empty($errors)) {
    echo '<div class="alert alert-danger" role="alert">';
    echo '<ul class="mb-0">';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</li>';
    }
    echo '</ul>';
    echo '</div>';
}

echo<<<_END
                        <form class="needs-validation" action="form_1details.php" method="post" novalidate>
                            <div class="mb-3">
                                <label for="region" class="form-label">Which region of the UK do you live in?</label>
                                <select class="form-select" id="region" name="region" required autofocus>
                                    <option value="" selected disabled>Choose your region...</option>
                                    <option value="North East">North East</option>
                                    <option value="North West">North West</option>
                                    <option value="Yorkshire & The Humber">Yorkshire & The Humber</option>
                                    <option value="East Midlands">East Midlands</option>
                                    <option value="West Midlands">West Midlands</option>
                                    <option value="East">East</option>
                                    <option value="London">London</option>
                                    <option value="South East">South East</option>
                                    <option value="South West">South West</option>
                                    <option value="Wales">Wales</option>
                                    <option value="Scotland">Scotland</option>
                                    <option value="Northern Ireland">Northern Ireland</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a valid UK region.
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="postcode" class="form-label">What is your postcode?</label>
                                <input type="text" class="form-control" id="postcode" name="postcode" 
                                       placeholder="e.g. M4 6AP" minlength="5" maxlength="8" 
                                       pattern="[A-Za-z]{1,2}[0-9][A-Za-z0-9]?\s?[0-9][A-Za-z]{2}" 
                                       title="Please enter a valid UK postcode" required>
                                <div class="form-text">We use this to provide regional carbon footprint comparisons</div>
                                <div class="invalid-feedback">
                                    Please enter a valid UK postcode.
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">Continue to Transport Details</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
// Bootstrap form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
_END;

require_once "footer.php";

?>