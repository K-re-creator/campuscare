<?php
declare(strict_types=1);

/**
 * Context-appropriate HTML Output Encoding.
 * Escapes untrusted user input safely to mitigate Cross-Site Scripting (XSS).
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Validates raw post variables against strict structural and domain boundaries.
 */
function validateBooking(array $input): array
{
    $values = [
        'service_id' => filter_var($input['service_id'] ?? null, FILTER_VALIDATE_INT),
        'slot_id'    => filter_var($input['slot_id'] ?? null, FILTER_VALIDATE_INT),
        'notes'      => trim((string)($input['notes'] ?? '')),
    ];

    $errors = [];

    // 1. Service validation
    if ($values['service_id'] === false || $values['service_id'] < 1) {
        $errors['service_id'] = 'Choose a valid service.';
    }

    // 2. Slot validation
    if ($values['slot_id'] === false || $values['slot_id'] < 1) {
        $errors['slot_id'] = 'Choose an available time.';
    }

    // 3. Notes length restriction (Boundary constraint checking)
    if (mb_strlen($values['notes']) > 500) {
        $errors['notes'] = 'Use 500 characters or fewer.';
    }

    return [$values, $errors];
}
