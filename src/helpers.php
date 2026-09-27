<?php
declare(strict_types=1);

/**
 * Context-appropriate HTML output encoding tool (e)
 * Prevents Cross-Site Scripting (XSS) by neutralizing dangerous characters.
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Normalizes and validates incoming booking data arrays
 * Separates safe values from descriptive validation errors.
 */
function validateBooking(array $input): array
{
    $values = [
        'service_id' => filter_var($input['service_id'] ?? null, FILTER_VALIDATE_INT),
        'slot_id'    => filter_var($input['slot_id'] ?? null, FILTER_VALIDATE_INT),
        'notes'      => trim((string)($input['notes'] ?? '')),
    ];

    $errors = [];

    // Rule: service_id must be a valid positive integer
    if ($values['service_id'] === false || $values['service_id'] < 1) {
        $errors['service_id'] = 'Choose a valid service.';
    }

    // Rule: slot_id must be a valid positive integer
    if ($values['slot_id'] === false || $values['slot_id'] < 1) {
        $errors['slot_id'] = 'Choose an available time.';
    }

    // Rule: notes must not exceed 500 characters (using standard strlen)
    if (strlen($values['notes']) > 500) {
        $errors['notes'] = 'Use 500 characters or fewer.';
    }

    return [$values, $errors];
}
