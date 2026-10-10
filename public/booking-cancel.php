<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Database.php';

// 1. Guard Rule: Only allow state-changing operations via POST channels
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Method Not Allowed');
}

// 2. Security Check: Block cross-site request forgery bugs
requireCsrf();

// 3. Authorization Check: Bounce anonymous connections
$currentUser = requireUser();

$bookingId = filter_var($_POST['booking_id'] ?? null, FILTER_VALIDATE_INT);

if ($bookingId === false || $bookingId === null) {
    http_response_code(400);
    exit('Invalid structural booking identification parameters passed.');
}

$pdo = database();
try {
    $pdo->beginTransaction();

    // 4. CRITICAL CORE RULES DESIGN: Filter strictly by BOTH record identification numbers AND matching owner codes
    $statement = $pdo->prepare(
        "UPDATE bookings 
         SET status = 'cancelled' 
         WHERE id = :id AND user_id = :user_id AND status = 'pending'"
    );

    $statement->execute([
        'id'      => $bookingId,
        'user_id' => $currentUser['id']
    ]);

    // Enforce error tracking if rows matched do not equal exactly 1 (signaling malicious ID tampering or state conflict)
    if ($statement->rowCount() !== 1) {
        // Lab Manual Status Standard mapping: 409 State Conflict or 403 Access Denied
        http_response_code(409);
        exit('Action denied: The record is either locked, processed, or belongs to another account path user identity profile.');
    }

    $pdo->commit();
    
    // Clean PRG pattern return routing redirection paths
    header('Location: /bookings.php', true, 303);
    exit;

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Cancellation transaction systemic error: ' . $e->getMessage());
    http_response_code(500);
    exit('An internal structural database tracking breakdown occurred.');
}
