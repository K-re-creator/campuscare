<?php
declare(strict_types=1);

// Initialize secure session management and helpers
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/helpers.php';

// If already logged in, skip this page and go straight to bookings
if (isset($_SESSION['user'])) {
    header('Location: /bookings.php', true, 303);
    exit;
}

// Check if there is an error message saved in the session from a failed login attempt
$errorMessage = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']); // Clear it immediately so it doesn't stay forever
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CampusCare</title>
    <!-- Link to your professional layout stylesheet asset -->
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body style="background-color: var(--bg-light); padding: 4rem 1rem;">

    <!-- Skip link for keyboard accessibility -->
    <a class="skip-link" href="#login-container">Skip to main content</a>

    <main id="login-container" style="max-width: 450px; margin: 0 auto;">
        
        <div id="booking-section" style="background: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <h2 style="text-align: center; border-bottom: none; padding-bottom: 0; margin-bottom: 2rem;">CampusCare Login</h2>

            <?php if ($errorMessage): ?>
                <div class="error-summary" role="alert">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: bold;"><?= e($errorMessage) ?></h3>
                </div>
            <?php endif; ?>

            <form action="/login-process.php" method="post" novalidate>
                <!-- 1. The mandatory anti-CSRF token hidden field -->
                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">

                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="student@example.test" required>
                </div>

                <div class="field" style="margin-bottom: 2rem;">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit">
                    Sign In
                </button>
            </form>
            
            <div style="margin-top: 1.5rem; text-align: center; font-size: 0.85rem; color: #666;">
                <p>Development Test Credentials:<br><strong>student@example.test</strong> / <strong>password123</strong></p>
            </div>
        </div>
        
    </main>

</body>
</html>
