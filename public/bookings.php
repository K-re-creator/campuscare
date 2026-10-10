<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Database.php';

// Enforce authentication guard check
$currentUser = requireUser();

try {
    // Prepare a secure parameter statement filtering strictly by active user identification values
    $statement = database()->prepare(
        'SELECT b.id, b.starts_at, b.status, b.notes, s.name AS service_name
         FROM bookings b 
         JOIN services s ON s.id = b.service_id
         WHERE b.user_id = :user_id
         ORDER BY b.created_at DESC, b.id DESC 
         LIMIT 50'
    );
    
    // Bind parameters natively to defeat SQL injection vectors
    $statement->execute(['user_id' => $currentUser['id']]);
    $bookings = $statement->fetchAll();
} catch (Throwable $e) {
    // If database connection hits a snag, log it privately and mask with a safe public user hint
    error_log('Dashboard booking fetch error: ' . $e->getMessage());
    $bookings = [];
    $systemError = 'Your historical booking logs could not be loaded safely right now.';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - CampusCare</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <a class="skip-link" href="#main">Skip to main content</a>

    <header class="site-header">
        <a class="brand" href="/">CampusCare</a>
        <div style="float: right; padding: 0.5rem;">
            <span>Logged in as: <strong><?= e($currentUser['email']) ?></strong></span>
            <form action="/logout.php" method="post" style="display: inline; margin-left: 1rem;">
                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                <button type="submit" style="background: none; border: none; color: #c5221f; cursor: pointer; text-decoration: underline;">
                    Log Out
                </button>
            </form>
        </div>
    </header>

    <nav aria-label="Primary">
        <ul class="nav-list">
            <li><a href="/">Services</a></li>
            <li><a href="/booking.php">Book Appointment Form</a></li>
            <li><a aria-current="page" href="/bookings.php">My bookings</a></li>
        </ul>
    </nav>

    <main id="main" style="max-width: 800px; margin: 2rem auto; padding: 1rem;">
        <h1>Your Service Bookings</h1>
        
        <?php if (isset($systemError)): ?>
            <div style="background-color: #fce8e6; color: #c5221f; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem;" role="alert">
                <?= e($systemError) ?>
            </div>
        <?php endif; ?>

        <p>Welcome back! Below are your scheduled appointments fetched securely from the database backend.</p>

        <?php if (empty($bookings)): ?>
            <p>You do not have any active appointments scheduled at this moment.</p>
        <?php else: ?>
            <div class="table-responsive">
                        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                <thead>
                    <tr style="background-color: #17365d; color: white; text-align: left;">
                        <th style="padding: 0.75rem; border: 1px solid #dee2e6;">Service Name</th>
                        <th style="padding: 0.75rem; border: 1px solid #dee2e6;">Preferred Date & Time</th>
                        <th style="padding: 0.75rem; border: 1px solid #dee2e6;">Status</th>
                        <th style="padding: 0.75rem; border: 1px solid #dee2e6;">Consultation Notes</th>
                        <th style="padding: 0.75rem; border: 1px solid #dee2e6;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td style="padding: 0.75rem; border: 1px solid #dee2e6;"><strong><?= e($booking['service_name']) ?></strong></td>
                            <td style="padding: 0.75rem; border: 1px solid #dee2e6;"><?= e($booking['starts_at']) ?></td>
                            <td style="padding: 0.75rem; border: 1px solid #dee2e6;">
                                <span style="padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem; font-weight: bold; background: #e8f0fe; color: #174f88;">
                                    <?= e($booking['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #dee2e6; font-style: italic; color: #555;">
                                <?= $booking['notes'] ? e($booking['notes']) : 'No additional notes added.' ?>
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #dee2e6;">
                                <!-- If the booking status is still pending, provide a defensive secure POST cancellation entry form -->
                                <?php if ($booking['status'] === 'pending'): ?>
                                    <form action="/booking-cancel.php" method="post" onsubmit="return confirm('Are you sure you want to cancel this appointment schedule entry?');" style="display: inline;">
                                        
                                        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                                        <input type="hidden" name="booking_id" value="<?= e((string)$booking['id']) ?>">
                                        <button type="submit" style="padding: 0.25rem 0.5rem; background-color: #c5221f; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">
                                            Cancel
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size: 0.85rem; color: #777;">Locked</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>

        <?php endif; ?>
    </main>

    <footer>
        <p>CampusCare learning project</p>
    </footer>
</body>
</html>
