<?php
declare(strict_types=1);
// Fallback if variables are not preset by the parent script file path
$values = $values ?? ['service_id' => '', 'slot_id' => '', 'notes' => '', 'date' => ''];
$errors = $errors ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment - CampusCare</title>
    <!-- Link to your professional layout stylesheet asset -->
    <link rel="stylesheet" href="/assets/css/app.css">
    <script src="/assets/js/app.js" defer></script>
</head>
<body style="background-color: var(--bg-light); padding: 2rem 1rem;">

    <!-- Skip link for keyboard accessibility -->
    <a class="skip-link" href="#booking-form-container">Skip to main content</a>

    <main id="booking-form-container" style="max-width: 600px; margin: 0 auto;">
        
        <div style="margin-bottom: 1.5rem;">
            <a href="/" style="color: var(--brand); font-weight: bold; text-decoration: none;">&larr; Back to Service Catalogue</a>
        </div>

        <div id="booking-section">
            <h2>Request an Appointment</h2>

            <!-- Lab 5 Requirement: Accessible Error Summary Block -->
            <?php if ($errors !== []): ?>
                <div class="error-summary" role="alert" aria-labelledby="error-summary-title">
                    <h3 id="error-summary-title">There was a problem with your submission</h3>
                    <ul>
                        <?php foreach ($errors as $field => $message): ?>
                            <li><a href="#<?= e($field) ?>"><?= e($message) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="booking-form" action="/booking-create.php" method="post" novalidate>
                <!-- Server-Side Request Modification Countermeasure Injection Guard -->
                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">

                <div class="field">
                    <label for="service_id">Service Selection</label>
                    <select id="service_id" name="service_id" 
                            aria-describedby="service_error" 
                            aria-invalid="<?= isset($errors['service_id']) ? 'true' : 'false' ?>" required>
                        <option value="">Choose a service</option>
                        <option value="1" <?= (string)$values['service_id'] === '1' ? 'selected' : '' ?>>Academic Advising</option>
                        <option value="2" <?= (string)$values['service_id'] === '2' ? 'selected' : '' ?>>IT Support Counter</option>
                        <option value="3" <?= (string)$values['service_id'] === '3' ? 'selected' : '' ?>>Student Health Clinic</option>
                    </select>
                    <?php if (isset($errors['service_id'])): ?>
                        <p id="service_error" class="error" role="alert"><?= e($errors['service_id']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="slots">Available Times</label>
                    <p id="slot-status" aria-live="polite">Choose a service to see available times.</p>
                    <select id="slots" name="slot_id" 
                            aria-describedby="slots_error"
                            aria-invalid="<?= isset($errors['slot_id']) ? 'true' : 'false' ?>" required>
                        <option value="">Choose a time</option>
                        <option value="101" <?= (string)$values['slot_id'] === '101' ? 'selected' : '' ?>>Monday 09:00</option>
                        <option value="102" <?= (string)$values['slot_id'] === '102' ? 'selected' : '' ?>>Monday 10:30</option>
                    </select>
                    <?php if (isset($errors['slot_id'])): ?>
                        <p id="slots_error" class="error" role="alert"><?= e($errors['slot_id']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="date">Preferred Date</label>
                    <p id="date-help">Choose a weekday within the next 30 days.</p>
                    <input id="date" name="date" type="date" 
                           value="<?= e((string)($values['date'] ?? '')) ?>"
                           aria-describedby="date-help date_error"
                           aria-invalid="<?= isset($errors['date']) ? 'true' : 'false' ?>" required>
                    <?php if (isset($errors['date'])): ?>
                        <p id="date_error" class="error" role="alert"><?= e($errors['date']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="notes">Additional Consultation Notes</label>
                    <textarea id="notes" name="notes" 
                              aria-describedby="notes_error"
                              aria-invalid="<?= isset($errors['notes']) ? 'true' : 'false' ?>"><?= e($values['notes']) ?></textarea>
                    <?php if (isset($errors['notes'])): ?>
                        <p id="notes_error" class="error" role="alert"><?= e($errors['notes']) ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit">Request appointment</button>
            </form>
        </div>
    </main>
</body>
</html>
