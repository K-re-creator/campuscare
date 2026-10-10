<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/helpers.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success - CampusCare</title>
    <!-- Link to your professional layout stylesheet asset -->
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <a class="skip-link" href="#main">Skip to main content</a>

    <header class="site-header">
        <a class="brand" href="/">CampusCare</a>
        <?php if (isset($_SESSION['user'])): ?>
            <div style="float: right; padding: 0.5rem; font-size: 0.95rem;">
                <span>Logged in as: <strong><?= e($_SESSION['user']['email']) ?></strong></span>
            </div>
        <?php endif; ?>
    </header>

    <nav aria-label="Primary">
        <ul class="nav-list">
            <li><a href="/">Services</a></li>
            <li><a href="/booking.php">Book Appointment Form</a></li>
            <li><a href="/bookings.php">My bookings</a></li>
        </ul>
    </nav>

    <main id="main" style="max-width: 600px; margin: 3rem auto; text-align: center;">
        
        <div id="booking-section" style="background: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 3rem 2rem;">
            <!-- Modern Visual Anchor Icon Checkmark Symbol -->
            <div style="font-size: 3.5rem; color: #2e7d32; margin-bottom: 1rem;">✓</div>
            
            <h2 style="border: none; padding: 0; margin-bottom: 1rem; color: #2e7d32;">Thank you!</h2>
            
            <p style="font-size: 1.05rem; color: #4a5568; margin-bottom: 2.5rem; line-height: 1.6;">
                Your booking request was successfully recorded without form resubmission loops. 
                The system database has safely committed your transaction records.
            </p>

            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="/bookings.php" style="display: inline-block; background-color: var(--brand); color: white; text-decoration: none; padding: 0.65rem 1.25rem; font-weight: 600; border-radius: var(--radius); font-size: 0.95rem;">
                    View My Bookings
                </a>
                <a href="/" style="display: inline-block; background-color: var(--bg-light); color: var(--brand); text-decoration: none; padding: 0.65rem 1.25rem; font-weight: 600; border: 1px solid var(--border-base); border-radius: var(--radius); font-size: 0.95rem;">
                    Back to Catalog
                </a>
            </div>
        </div>

    </main>

    <footer>
        <p>CampusCare learning project</p>
    </footer>
</body>
</html>
