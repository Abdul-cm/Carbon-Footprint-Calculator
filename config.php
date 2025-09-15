<?php
/**
 * Carbon Footprint Calculator - Database Configuration
 * 
 * This file contains database configuration settings.
 * Make sure to update these settings for your environment.
 * 
 * @author Abdul-cm
 * @version 2.0
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'carbon_footprint_calc');

// Application configuration
define('TIMEZONE', 'Europe/London');
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds

/**
 * Database connection function with error handling
 * @return mysqli|false Database connection or false on failure
 */
function getDatabaseConnection() {
    try {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($connection->connect_error) {
            error_log("Database connection failed: " . $connection->connect_error);
            return false;
        }
        
        // Set charset to prevent character encoding issues
        $connection->set_charset("utf8");
        
        return $connection;
    } catch (Exception $e) {
        error_log("Database connection exception: " . $e->getMessage());
        return false;
    }
}

/**
 * Sanitize input data to prevent XSS and other attacks
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate postcode format (UK postcodes)
 * @param string $postcode Postcode to validate
 * @return bool True if valid, false otherwise
 */
function validatePostcode($postcode) {
    $postcode = strtoupper(str_replace(' ', '', $postcode));
    $pattern = '/^[A-Z]{1,2}\d[A-Z\d]?\s?\d[A-Z]{2}$/';
    return preg_match($pattern, $postcode);
}

/**
 * Validate region selection
 * @param string $region Region to validate
 * @return bool True if valid, false otherwise
 */
function validateRegion($region) {
    $validRegions = [
        'North East', 'North West', 'Yorkshire & The Humber',
        'East Midlands', 'West Midlands', 'East', 'London',
        'South East', 'South West', 'Wales', 'Scotland', 'Northern Ireland'
    ];
    return in_array($region, $validRegions);
}

/**
 * Start secure session
 */
function startSecureSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
        
        // Regenerate session ID to prevent session fixation
        if (!isset($_SESSION['initiated'])) {
            session_regenerate_id(true);
            $_SESSION['initiated'] = true;
        }
        
        // Set session timeout
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
            session_unset();
            session_destroy();
            session_start();
        }
        $_SESSION['last_activity'] = time();
    }
}
?>