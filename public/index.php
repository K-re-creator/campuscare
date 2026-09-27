<?php
declare(strict_types=1);
$requestId = bin2hex(random_bytes(4));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusCare Service Booking</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <script src="/assets/js/app.js" defer></script>
</head>
<body>
    <!-- Skip link must be the very first focusable element -->
    <a class="skip-link" href="#main">Skip to main content</a>

    <header class="site-header">
        <a class="brand" href="/">CampusCare</a>
    </header>

    <nav aria-label="Primary">
        <ul class="nav-list">
            <li><a aria-current="page" href="/">Services</a></li>
            <li><a href="/booking.php">Book Appointment Form</a></li>
            <li><a href="/bookings.php">My bookings</a></li>
        </ul>
    </nav>

    <main id="main">
        <h1>Available campus services</h1>
        <p>Development request tracing ID: <?= htmlspecialchars($requestId) ?></p>

        <!-- Service Catalogue Grid Component -->
        <div class="services" id="services">
            <article class="card">
                <h2>Academic Advising</h2>
                <p>One-on-one session with your faculty path advisor.</p>
                <p><strong>Duration:</strong> 45 minutes</p>
                <a href="#booking-form" aria-label="Book academic advising session">Book academic advising</a>
            </article>

            <article class="card">
                <h2>IT Support Counter</h2>
                <p>On-campus technical hardware diagnostics and configuration support.</p>
                <p><strong>Duration:</strong> 15 minutes</p>
                <a href="#booking-form" aria-label="Book IT support service">Book IT support</a>
            </article>

            <article class="card">
                <h2>Student Health Clinic</h2>
                <p>Non-emergency medical consultations and wellness check-ups.</p>
                <p><strong>Duration:</strong> 30 minutes</p>
                <a href="#booking-form" aria-label="Book student health appointment">Book health check-up</a>
            </article>
        </div>

        <!-- Booking Form Section Container -->
        <section id="booking-section" aria-labelledby="form-heading">
            <h2 id="form-heading">Request an Appointment</h2>
                <form id="booking-form" action="/booking-create.php" method="post">
    <div class="field">
        <!-- Native, explicitly coupled label -->
        <label for="service">Service Selection</label>
        <select id="service" name="service_id" required>
            <option value="">Choose a service</option>
            <option value="1">Academic advising</option>
            <option value="2">IT Support Counter</option>
            <option value="3">Student Health Clinic</option>
        </select>
        <label for="slots">Available Times</label>
    <!-- Screen readers will announce updates inside this container -->
    <p id="slot-status" aria-live="polite">Choose a service to see available times.</p>
    <select id="slots" name="slot_id" required>
        <option value="">Choose a time</option>
    </select>
    </div>

    <div class="field">
        <label for="date">Preferred Date</label>
        <!-- Accessible association connecting help and errors using aria-describedby -->
        <p id="date-help">Choose a weekday within the next 30 days.</p>
        <input id="date" name="date" type="date" required aria-describedby="date-help date-error">
        <p id="date-error" class="error" aria-live="polite"></p>
    </div>

    <div class="field">
                    <!-- FIXED: Added missing text notes area matching validateBooking() structural schema -->
                    <label for="notes">Additional Consultation Notes</label>
                    <textarea id="notes" name="notes"></textarea>
                </div>
    <button type="submit">Request appointment</button>
</form>

        </section>
    </main>

    <footer>
        <p>CampusCare learning project</p>
    </footer>
</body>
</html>
