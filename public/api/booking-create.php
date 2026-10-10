<?php
declare(strict_types=1);

// 1. Boot up structural configuration dependencies
require_once __DIR__ . '/../../src/bootstrap.php';
require_once __DIR__ . '/../../src/helpers.php';
require_once __DIR__ . '/../../src/Database.php';

// Enforce json transmission headers
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store'); // Stop proxy caching layers

/**
 * Standardized global error helper producing JSON Problem responses (RFC 7807 variant)
 */
function jsonProblem(int $status, string $title, array $errors = []): never
{
    http_response_code($status);
    echo json_encode([
        'title'  => $title,
        'errors' => $errors
    ], JSON_THROW_ON_ERROR);
    exit;
}

// 2. Route Guard: Enforce strict POST channel access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    jsonProblem(405, 'Method Not Allowed');
}

// 3. Authorization Check: Ensure the incoming request has a valid session context
if (!isset($_SESSION['user'])) {
    jsonProblem(401, 'Authentication Required');
}
$currentUser = $_SESSION['user'];

// 4. CSRF Header Check: For APIs, we check custom headers to block cross-origin requests
$csrfTokenHeader = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrfTokenHeader)) {
    jsonProblem(403, 'Invalid or missing CSRF security headers');
}

// 5. Read the raw application/json payload body content input stream
$rawInput = file_get_contents('php://input');
try {
    $inputData = json_decode($rawInput, true, 32, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    jsonProblem(400, 'Malformed JSON payload structure');
}

// 6. Validate values using existing helper schemas
[$values, $errors] = validateBooking(is_array($inputData) ? $inputData : []);
$startsAtText = trim((string)($inputData['date'] ?? '')) . ' 09:00:00';

if ($errors !== []) {
    jsonProblem(422, 'Validation failed', $errors);
}

// 7. Execute Data Insertion via Database Transactions
$pdo = database();
try {
    $pdo->beginTransaction();

    $insert = $pdo->prepare(
        "INSERT INTO bookings (user_id, service_id, starts_at, notes, status)
         VALUES (:user_id, :service_id, :starts_at, :notes, 'pending')"
    );

    $insert->execute([
        'user_id'    => $currentUser['id'],
        'service_id' => $values['service_id'],
        'starts_at'  => $startsAtText,
        'notes'      => $values['notes']
    ]);

    $newBookingId = $pdo->lastInsertId();
    $pdo->commit();

    // API Contract Success Path: 201 Created status plus target routing paths
    http_response_code(201);
    header('Location: /api/bookings/' . $newBookingId);
    echo json_encode([
        'success' => true,
        'data' => ['id' => $newBookingId]
    ], JSON_THROW_ON_ERROR);
    exit;

} catch (Throwable $error) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('API Booking Creation Failure: ' . $error->getMessage());

    if (str_contains($error->getMessage(), '1062') || str_contains($error->getMessage(), '23000')) {
        jsonProblem(409, 'The selected time slot is already fully booked.');
    } else {
        jsonProblem(500, 'The request could not be completed safely due to an internal system fault.');
    }
}
