<?php
declare(strict_types=1);

// 1. Pull in your security and session files
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/bootstrap.php'; 

// 2. Reject the request if it isn't a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Method Not Allowed');
}

// 3. Stop the request immediately if a hacker tries to forge the form submission
requireCsrf();

// 4. Extract parameters and validate the data
[$values, $errors] = validateBooking($_POST);

// Temporary list of available service IDs (1 = Advising, 2 = IT, 3 = Health)
$allowedServices =;
if ($values['service_id'] !== false && !in_array($values['service_id'], $allowedServices, true)) {
    $errors['service_id'] = 'The selected service is unavailable.';
}

// If there are validation errors, send a 422 status and show the form again
if ($errors !== []) {
    http_response_code(422);
    require __DIR__ . '/../templates/booking-form.php';
    exit;
}

// If everything goes perfectly, redirect safely to the success screen
header('Location: /booking-success.php', true, 303);
exit;