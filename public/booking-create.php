<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/helpers.php';

// 1. Route Guard: Reject anything that isn't a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Method Not Allowed');
}

// 2. Extract and Validate values using helpers
[$values, $errors] = validateBooking($_POST);

// 3. Temporary domain rule check (until database integration in Lab 7/8)
$allowedServices = [1, 2, 3];
if ($values['service_id'] !== false && !in_array($values['service_id'], $allowedServices, true)) {
    $errors['service_id'] = 'The selected service is unavailable.';
}

// 4. Handle validation failures (Redisplay form with errors)
if ($errors !== []) {
    http_response_code(422); // Unprocessable Entity
    
    // Require your form template context directly
    // This allows the template to read $values and $errors seamlessly
    require __DIR__ . '/../templates/booking-form.php';
    exit;
}

// 5. Success Path: Apply Post/Redirect/Get (PRG) pattern with a 303 See Other redirect
header('Location: /booking-success.php', true, 303);
exit;