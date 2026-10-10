<?php
declare(strict_types=1);

// Initialize secure session management context
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/helpers.php';

// Enforce authentication guard: Ensure only logged-in users can access the form
$currentUser = requireUser();

// Provide clean fallback structural states for the form template context
$values = ['service_id' => '', 'slot_id' => '', 'notes' => '', 'date' => ''];
$errors = []; // <-- FIXED: Added the proper empty array brackets here

// Render the standalone template file layout cleanly
require __DIR__ . '/../templates/booking-form.php';
