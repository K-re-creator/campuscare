"use strict";

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("#booking-form");
    if (!form) return; // Halt cleanly if loaded on a page without the booking container template

    form.addEventListener("submit", async (event) => {
        // Feature check: Abort interception loop cleanly if the browser lacks modern Fetch APIs
        if (!window.fetch) return;

        // Prevent native window navigation reloads
        event.preventDefault();

        // Clear any old, historically rendered error banners on screen
        const existingSummary = document.querySelector(".error-summary");
        if (existingSummary) existingSummary.remove();
        document.querySelectorAll(".error").forEach(el => el.remove());
        document.querySelectorAll("[aria-invalid]").forEach(el => el.setAttribute("aria-invalid", "false"));

        const submitButton = form.querySelector("button[type='submit']");
        if (submitButton) submitButton.disabled = true;

        // Construct standard payload objects mapping form element key-value strings
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            // Asynchronous Fetch Delivery Gate targeting your new JSON API router location
            const response = await fetch("/api/booking-create.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    // Pass the anti-CSRF token hidden field value within custom header slots
                    "X-CSRF-Token": payload.csrf || ""
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (!response.ok) {
                // Throw validation or conflict details down to the user interaction interface catch loop
                throw new Error(result.title || "Your appointment request failed to process.");
            }

            // Success Path: Redirect main window location history loops smoothly to completion layout routes
            window.location.assign("/booking-success.php");

        } catch (error) {
            if (submitButton) submitButton.disabled = false;
            
            // Build and inject an accessible error notification container layout block
            const errorSummary = document.createElement("div");
            errorSummary.className = "error-summary";
            errorSummary.setAttribute("role", "alert");
            errorSummary.style.cssText = "border: 2px solid red; padding: 1rem; margin-bottom: 1.5rem;";
            errorSummary.innerHTML = `<h3 style="margin-top:0; color:red;">Form submission error</h3><p>${escapeHtml(error.message)}</p>`;
            
            form.parentNode.insertBefore(errorSummary, form);
            errorSummary.scrollIntoView({ behavior: "smooth" });
        }
    });
});

/**
 * Utility string sanitization tool preventing code injection parameters within dynamic template inserts.
 */
function escapeHtml(str) {
    return str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
