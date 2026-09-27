<?php
declare(strict_types=1);
// Ensure this file is always included in a context where $values and $errors are defined
$values = $values ?? ['service_id' => '', 'slot_id' => '', 'notes' => ''];
$errors = $errors ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Book an Appointment</title>
</head>
<body>
    <main>
        <h1>Schedule Appointment</h1>

        <form action="/booking-create.php" method="post" novalidate>
            <div>
                <label for="service_id">Service Preference</label>
                <select id="service_id" name="service_id" aria-describedby="service_error">
                    <option value="">-- Choose Option --</option>
                    <option value="1" <?= (string)$values['service_id'] === '1' ? 'selected' : '' ?>>Health Assessment</option>
                    <option value="2" <?= (string)$values['service_id'] === '2' ? 'selected' : '' ?>>Vaccination Service</option>
                    <option value="3" <?= (string)$values['service_id'] === '3' ? 'selected' : '' ?>>Mental Health Triage</option>
                </select>
                <?php if (isset($errors['service_id'])): ?>
                    <p id="service_error" style="color:red;" role="alert"><?= e($errors['service_id']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="slot_id">Available Slot Reference ID</label>
                <input type="number" id="slot_id" name="slot_id" value="<?= e((string)($values['slot_id'] ?: '')) ?>" aria-describedby="slot_error">
                <?php if (isset($errors['slot_id'])): ?>
                    <p id="slot_error" style="color:red;" role="alert"><?= e($errors['slot_id']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="notes">Additional Consultation Notes</label>
                <textarea id="notes" name="notes" aria-describedby="notes_error"><?= e($values['notes']) ?></textarea>
                <?php if (isset($errors['notes'])): ?>
                    <p id="notes_error" style="color:red;" role="alert"><?= e($errors['notes']) ?></p>
                <?php endif; ?>
            </div>

            <button type="submit">Submit Request</button>
        </form>
    </main>
</body>
</html>
