<?php
declare(strict_types=1);
$requestId = bin2hex(random_bytes(4));
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>CampusCare</title></head>
<body>
<main>
<h1>CampusCare Service Booking</h1>
<p>Development request: <?= htmlspecialchars($requestId) ?></p>
</main>
</body>
</html>