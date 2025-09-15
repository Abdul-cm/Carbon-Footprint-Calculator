<?php
/**
 * Carbon Footprint Calculator - Step 2: Personal Transportation
 * 
 * This form collects information about personal vehicle usage.
 * 
 * @version 2.0
 */

require_once "header.php";

// Check if user has completed step 1
if (!isset($_SESSION['region']) || !isset($_SESSION['postcode'])) {
    header("Location: form_1details.php");
    exit();
}

echo<<<_END
    <br>
    <div class="container">
        <div class="progress mb-4">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 34%;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100">Step 2 of 5</div>
        </div>
    </div>
    
    <div class="container" id="form">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4" id="formHeader">Personal Transportation</h2>
                        <p class="text-center text-muted mb-4">Tell us about your vehicle usage and driving habits</p>
                        
                        <form class="needs-validation" action="form_3publictransport.php" method="post" novalidate>
                            <div class="mb-3">
                                <label for="driveQuestion" class="form-label">Do you drive a car or motorbike?</label>
                                <select class="form-select" id="driveQuestion" name="driveQuestion" required>
                                    <option value="" selected disabled>Choose an option...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select whether you drive a vehicle.
                                </div>
                            </div>
                            
                            <div class="mb-3" id="vehicleTypeContainer" style="display: none;">
                                <label for="vehicleType" class="form-label">What type of vehicle do you drive?</label>
                                <select class="form-select" id="vehicleType" name="vehicleType">
                                    <option value="" selected disabled>Choose your vehicle type...</option>
                                    <option value="Car Petrol">Car (petrol)</option>
                                    <option value="Car Diesel">Car (diesel)</option>
                                    <option value="Car Hybrid">Car (hybrid)</option>
                                    <option value="Car Electric">Car (electric)</option>
                                    <option value="Motorbike">Motorbike</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select your vehicle type.
                                </div>
                            </div>
                            
                            <div class="mb-4" id="mileageContainer" style="display: none;">
                                <label class="form-label">How many miles do you drive?</label>
                                <div class="form-text mb-3">Enter your mileage in ONE of the boxes below</div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" placeholder="Weekly miles" 
                                               min="0" max="5000" id="vehicleMilesWeekly" name="vehicleMilesWeekly">
                                        <small class="form-text text-muted">Per week</small>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" placeholder="Monthly miles" 
                                               min="0" max="50000" id="vehicleMilesMonthly" name="vehicleMilesMonthly">
                                        <small class="form-text text-muted">Per month</small>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" placeholder="Yearly miles" 
                                               min="0" max="100000" id="vehicleMilesYearly" name="vehicleMilesYearly">
                                        <small class="form-text text-muted">Per year</small>
                                    </div>
                                </div>
                                <div class="invalid-feedback d-block" id="mileageError" style="display: none !important;">
                                    Please enter your mileage in one of the fields above.
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                <a href="form_1details.php" class="btn btn-outline-secondary">← Previous</a>
                                <button type="submit" class="btn btn-success btn-lg">Continue to Public Transport</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
// Enhanced form validation and interaction
document.addEventListener('DOMContentLoaded', function() {
    const driveQuestion = document.getElementById('driveQuestion');
    const vehicleTypeContainer = document.getElementById('vehicleTypeContainer');
    const mileageContainer = document.getElementById('mileageContainer');
    const vehicleType = document.getElementById('vehicleType');
    const weeklyMiles = document.getElementById('vehicleMilesWeekly');
    const monthlyMiles = document.getElementById('vehicleMilesMonthly');
    const yearlyMiles = document.getElementById('vehicleMilesYearly');
    const mileageError = document.getElementById('mileageError');
    
    // Show/hide vehicle details based on drive question
    driveQuestion.addEventListener('change', function() {
        if (this.value === 'Yes') {
            vehicleTypeContainer.style.display = 'block';
            mileageContainer.style.display = 'block';
            vehicleType.required = true;
        } else {
            vehicleTypeContainer.style.display = 'none';
            mileageContainer.style.display = 'none';
            vehicleType.required = false;
            vehicleType.value = '';
            weeklyMiles.value = '';
            monthlyMiles.value = '';
            yearlyMiles.value = '';
        }
    });
    
    // Mutual exclusion for mileage inputs
    function handleMileageInput(activeField, otherFields) {
        activeField.addEventListener('input', function() {
            if (this.value !== '') {
                otherFields.forEach(field => {
                    field.disabled = true;
                    field.value = '';
                });
            } else {
                otherFields.forEach(field => {
                    field.disabled = false;
                });
            }
        });
    }
    
    handleMileageInput(weeklyMiles, [monthlyMiles, yearlyMiles]);
    handleMileageInput(monthlyMiles, [weeklyMiles, yearlyMiles]);
    handleMileageInput(yearlyMiles, [weeklyMiles, monthlyMiles]);
    
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Check if driving and validate accordingly
        if (driveQuestion.value === 'Yes') {
            if (!vehicleType.value) {
                isValid = false;
                vehicleType.classList.add('is-invalid');
            }
            
            if (!weeklyMiles.value && !monthlyMiles.value && !yearlyMiles.value) {
                isValid = false;
                mileageError.style.display = 'block';
            } else {
                mileageError.style.display = 'none';
            }
        }
        
        if (!form.checkValidity() || !isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    });
});
</script>
_END;

require_once "footer.php";

?>