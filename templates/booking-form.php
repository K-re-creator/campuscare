<?php
// Initialize empty arrays if this is the initial GET request so PHP doesn't throw notices
$values = $values ?? ['service_id' => '', 'slot_id' => '', 'notes' => ''];
$errors = $errors ?? [];
?>

<!-- If there are any server errors, render an accessible error summary box at the top -->
<?php if (!empty($errors)): ?>
    <div class="error-summary" role="alert" aria-labelledby="error-summary-title" tabindex="-1">
        <h2 id="error-summary-title">There is a problem with your submission</h2>
        <ul>
            <?php foreach ($errors as $field => $message): ?>
                <li><a href="#<?= e($field) ?>"><?= e($message) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form id="booking-form" action="/booking-create.php" method="post">

<!-- 🔐 CSRF Anti-Forgery Token Injection Vector -->
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    
    <!-- 1. Service Field -->
    <div class="field">
        <label for="service_id">Service</label>
        <select id="service_id" name="service_id" required 
                aria-invalid="<?= isset($errors['service_id']) ? 'true' : 'false' ?>"
                aria-describedby="<?= isset($errors['service_id']) ? 'service_id-error' : '' ?>">
            <option value="">Choose a service</option>
            <option value="1" <?= $values['service_id'] === 1 ? 'selected' : '' ?>>Academic advising</option>
            <option value="2" <?= $values['service_id'] === 2 ? 'selected' : '' ?>>IT Support Counter</option>
            <option value="3" <?= $values['service_id'] === 3 ? 'selected' : '' ?>>Health Services</option>
        </select>
        <?php if (isset($errors['service_id'])): ?>
            <p id="service_id-error" class="error-text"><?= e($errors['service_id']) ?></p>
        <?php endif; ?>
    </div>

    <!-- 2. Time Slot Field -->
    <div class="field">
        <label for="slot_id">Available Times</label>
        <p id="slot-status" aria-live="polite">Choose a service to see available times.</p>
        <select id="slot_id" name="slot_id" required
                aria-invalid="<?= isset($errors['slot_id']) ? 'true' : 'false' ?>"
                aria-describedby="slot-status <?= isset($errors['slot_id']) ? 'slot_id-error' : '' ?>">
            <option value="">Choose a time</option>
            <!-- In Lab 8, these will be dynamically fetched from the DB -->
            <option value="101" <?= $values['slot_id'] === 101 ? 'selected' : '' ?>>Monday 09:00</option>
            <option value="102" <?= $values['slot_id'] === 102 ? 'selected' : '' ?>>Monday 10:30</option>
        </select>
        <?php if (isset($errors['slot_id'])): ?>
            <p id="slot_id-error" class="error-text"><?= e($errors['slot_id']) ?></p>
        <?php endif; ?>
    </div>

    <!-- 3. Optional Notes Field (Crucial for testing XSS and character limits) -->
    <div class="field">
        <label for="notes">Additional Notes (Optional)</label>
        <textarea id="notes" name="notes" rows="4" cols="50"
                aria-invalid="<?= isset($errors['notes']) ? 'true' : 'false' ?>"
                aria-describedby="<?= isset($errors['notes']) ? 'notes-error' : '' ?>"><?= e($values['notes']) ?></textarea>
        <?php if (isset($errors['notes'])): ?>
            <p id="notes-error" class="error-text"><?= e($errors['notes']) ?></p>
        <?php endif; ?>
    </div>

    <button type="submit">Request appointment</button>
</form>
