<?php
declare(strict_types=1);
// Fallback if variables are not preset
$values = $values ?? ['service_id' => '', 'slot_id' => '', 'notes' => ''];
$errors = $errors ?? [];
?>

<!-- Lab 5 Requirement: Accessible Error Summary Block -->
<?php if ($errors !== []): ?>
    <div class="error-summary" role="alert" aria-labelledby="error-summary-title" style="border: 2px solid red; padding: 1rem; margin-bottom: 1.5rem;" playbook-focus>
        <h3 id="error-summary-title" style="margin-top: 0; color: red;">There was a problem with your submission</h3>
        <ul>
            <?php foreach ($errors as $field => $message): ?>
                <li><a href="#<?= e($field) ?>"><?= e($message) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="/booking-create.php" method="post" novalidate>
    <div class="field" style="margin-bottom: 1rem;">
        <label for="service_id">Service Preference</label>
        <select id="service_id" name="service_id" 
                aria-describedby="service_error" 
                aria-invalid="<?= isset($errors['service_id']) ? 'true' : 'false' ?>">
            <option value="">-- Choose Option --</option>
            <option value="1" <?= (string)$values['service_id'] === '1' ? 'selected' : '' ?>>Academic Advising</option>
            <option value="2" <?= (string)$values['service_id'] === '2' ? 'selected' : '' ?>>IT Support Counter</option>
            <option value="3" <?= (string)$values['service_id'] === '3' ? 'selected' : '' ?>>Student Health Clinic</option>
        </select>
        <?php if (isset($errors['service_id'])): ?>
            <p id="service_error" style="color:red; margin: 0.25rem 0 0 0;" role="alert"><?= e($errors['service_id']) ?></p>
        <?php endif; ?>
    </div>

    <div class="field" style="margin-bottom: 1rem;">
        <label for="slot_id">Available Slot Reference ID</label>
        <input type="number" id="slot_id" name="slot_id" 
               value="<?= e((string)($values['slot_id'] ?: '')) ?>" 
               aria-describedby="slot_error"
               aria-invalid="<?= isset($errors['slot_id']) ? 'true' : 'false' ?>">
        <?php if (isset($errors['slot_id'])): ?>
            <p id="slot_error" style="color:red; margin: 0.25rem 0 0 0;" role="alert"><?= e($errors['slot_id']) ?></p>
        <?php endif; ?>
    </div>

    <div class="field" style="margin-bottom: 1rem;">
        <label for="notes">Additional Consultation Notes</label>
        <textarea id="notes" name="notes" 
                  aria-describedby="notes_error"
                  aria-invalid="<?= isset($errors['notes']) ? 'true' : 'false' ?>"><?= e($values['notes']) ?></textarea>
        <?php if (isset($errors['notes'])): ?>
            <p id="notes_error" style="color:red; margin: 0.25rem 0 0 0;" role="alert"><?= e($errors['notes']) ?></p>
        <?php endif; ?>
    </div>

    <button type="submit">Submit Request</button>
</form>
