<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/bootstrap.php';

// Initialize a blank error variable
$error = '';

// Fake temporary user account credentials to test with (Plaintext: "password123")
// We generate this hash securely using PHP's native password_hash algorithm
$testHash = password_hash('password123', PASSWORD_DEFAULT);

// Process the form only if the user clicked "Sign In"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedEmail = trim((string)($_POST['email'] ?? ''));
    $submittedPassword = (string)($_POST['password'] ?? '');

    // Security Rule: Use a generic error message so hackers don't know if the email exists
    if ($submittedEmail !== 'student@example.test' || !password_verify($submittedPassword, $testHash)) {
        $error = 'Email or password is incorrect.';
    } else {
         // SUCCESS! Securely rotate the session ID card to block session fixation attacks
        session_regenerate_id(true);
        
        // Save the authenticated user profile details directly into server memory
        $_SESSION['user'] = [
            'id' => 1,
            'email' => 'student@example.test',
            'role' => 'customer'
        ];
        $_SESSION['authenticated_at'] = time();

        // Safe Post/Redirect/Get jump back to your workspace index catalogue
        header('Location: /', true, 303);
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - CampusCare</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <main id="main" style="max-width: 400px; margin: 50px auto; padding: 20px;">
        <h1>Sign In</h1>

        <?php if (!empty($error)): ?>
            <div style="color: red; margin-bottom: 15px;" role="alert">
                <strong><?= e($error) ?></strong>
            </div>
        <?php endif; ?>

        <form action="/login.php" method="post">
            <div class="field" style="margin-bottom: 15px;">
                <label for="email">Email Address</label><br>
                <input type="email" id="email" name="email" required value="<?= e($submittedEmail ?? '') ?>">
            </div>

            <div class="field" style="margin-bottom: 15px;">
                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Sign In</button>
        </form>
    </main>
</body>
</html>