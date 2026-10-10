<?php
declare(strict_types=1);
// Fallback if variables are not preset by the parent script file path
$values = $values ?? ['service_id' => '', 'slot_id' => '', 'notes' => '', 'date' => ''];
$errors = $errors ?? [];
?>

<!-- Lab 5 Requirement: Accessible Error Summary Block -->
<?php if ($errors !== []): ?>
    <div class="error-summary" role="alert" aria-labelledby="error-summary-title" style="border: 2px solid red; padding: 1rem; margin-bottom: 1.5rem;">
        <h3 id="error-summary-title" style="margin-top: 0; color: red;">There was a problem with your submission</h3>
        <ul>
            <?php foreach ($errors as $field => $message): ?>
                <li><a href="#<?= e($field) ?>"><?= e($message) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="/booking-create.php" method="post" novalidate>
    <!-- Server-Side Request Modification Countermeasure Injection Guard -->
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">

    <div class="field" style="margin-bottom: 1rem;">
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
            <p id="service_error" style="color:red; margin: 0.25rem 0 0 0;" role="alert"><?= e($errors['service_id']) ?></p>
        <?php endif; ?>
    </div>

    <div class="field" style="margin-bottom: 1rem;">
        <label for="slots">Available Times</label>
        <p id="slot-status" aria-live="polite" style="margin: 0 0 0.25rem 0; font-size: 0.9rem; color: #555;">Choose a service to see available times.</p>
        <select id="slots" name="slot_id" 
                aria-describedby="slots_error"
                aria-invalid="<?= isset($errors['slot_id']) ? 'true' : 'false' ?>" required>
            <option value="">Choose a time</option>
            <!-- Temporary options for development testing -->
            <option value="101" <?= (string)$values['slot_id'] === '101' ? 'selected' : '' ?>>Monday 09:00</option>
            <option value="102" <?= (string)$values['slot_id'] === '102' ? 'selected' : '' ?>>Monday 10:30</option>
        </select>
        <?php if (isset($errors['slot_id'])): ?>
            <p id="slots_error" style="color:red; margin: 0.25rem 0 0 0;" role="alert"><?= e($errors['slot_id']) ?></p>
        <?php endif; ?>
    </div>

    <div class="field" style="margin-bottom: 1rem;">
        <label for="date">Preferred Date</label>
        <p id="date-help" style="margin: 0 0 0.25rem 0; font-size: 0.9rem; color: #555;">Choose a weekday within the next 30 days.</p>
        <input id="date" name="date" type="date" 
               value="<?= e((string)($values['date'] ?? '')) ?>"
               aria-describedby="date-help date_error"
               aria-invalid="<?= isset($errors['date']) ? 'true' : 'false' ?>" required>
        <?php if (isset($errors['date'])): ?>
            <p id="date_error" style="color:red; margin: 0.25rem 0 0 0;" role="alert"><?= e($errors['date']) ?></p>
        <?php endif; ?>
    </div>

    <div class="field" style="margin-bottom: 1rem;">
        <label for="notes">Additional Consultation Notes</label>
        <textarea id="notes" name="notes" style="width: 100%; min-height: 80px;"
                  aria-describedby="notes_error"
                  aria-invalid="<?= isset($errors['notes']) ? 'true' : 'false' ?>"><?= e($values['notes']) ?></textarea>
        <?php if (isset($errors['notes'])): ?>
            <p id="notes_error" style="color:red; margin: 0.25rem 0 0 0;" role="alert"><?= e($errors['notes']) ?></p>
        <?php endif; ?>
    </div>

    <button type="submit" style="padding: 0.5rem 1rem; background-color: #17365d; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Request appointment
    </button>
</form>
