<?php
declare(strict_types=1);

// Set headers for JSON delivery
header('Content-Type: application/json; charset=utf-8');

// Validate the incoming service ID parameter
$serviceId = filter_input(INPUT_GET, 'serviceId', FILTER_VALIDATE_INT);
if (!$serviceId || $serviceId < 1) {
    http_response_code(422);
    echo json_encode(['error' => 'Choose a valid service']);
    exit;
}

// Temporary hardcoded slots data mapping serviceId -> slots array
$data = [
    1 => [
        ['id' => 101, 'label' => 'Monday 09:00'],
        ['id' => 102, 'label' => 'Monday 10:30']
    ],
    2 => [] // Simulates an empty result state
];

// Return data for the requested service, or an empty array if not found
echo json_encode($data[$serviceId] ?? [], JSON_THROW_ON_ERROR);