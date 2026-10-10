<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Database.php';

// 1. Guard Rule: Enforce that this route only accepts POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Method Not Allowed');
}

// 2. Security Check: Enforce anti-CSRF token verification before editing data
requireCsrf();

// 3. Authorization Check: Ensure the user is logged in
$currentUser = requireUser();

// 4. Extract and Validate fields using your helpers
[$values, $errors] = validateBooking($_POST);

// Simulate appointment date structure parameter alignment
$startsAtText = trim((string)($_POST['date'] ?? '')) . ' 09:00:00'; 

if ($errors !== []) {
    http_response_code(422);
    require __DIR__ . '/../templates/booking-form.php';
    exit;
}

// 5. Database Create Operation wrapped inside a Safe Concurrency Transaction
$pdo = database();
try {
    // Start transactional lockdown
    $pdo->beginTransaction();

    // Insert record with parameterized placeholders to block SQL injection
    $insert = $pdo->prepare(
        'INSERT INTO bookings (user_id, service_id, starts_at, notes, status)
         VALUES (:user_id, :service_id, :starts_at, :notes, \'pending\')'
    );

    $insert->execute([
        'user_id'    => $currentUser['id'],
        'service_id' => $values['service_id'],
        'starts_at'  => $startsAtText,
        'notes'      => $values['notes']
    ]);

    // Commit changes safely to storage disk if everything passes
    $pdo->commit();

    // Success PRG redirect to clear history buffers
    header('Location: /booking-success.php', true, 303);
    exit;

} catch (Throwable $error) {
    // If anything fails or triggers a unique key collision, pull back changes completely
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Log the actual backend error details silently for diagnostics
    error_log('Booking database storage failure: ' . $error->getMessage());

    // Check if error is due to a duplicate slot collision (MySQL error code 23000)
    if (str_contains($error->getMessage(), '1062') || str_contains($error->getMessage(), '23000')) {
        $errors['slot_id'] = 'The selected appointment time slot is already fully booked.';
        http_response_code(409); // Conflict status code
    } else {
        $errors['service_id'] = 'The appointment request could not be saved right now due to a backend system fault.';
        http_response_code(500); // Server error code
    }

    // Pull form back up with errors populated cleanly
    require __DIR__ . '/../templates/booking-form.php';
    exit;
}
