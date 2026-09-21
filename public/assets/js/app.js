"use strict";

// Select the required interactive elements from the DOM template
const form = document.querySelector("#booking-form");
const service = document.querySelector("#service");
const date = document.querySelector("#date");
const dateError = document.querySelector("#date-error");

// Defensive Check: Fail immediately during development if any markup element is missing or renamed
if (!form || !service || !date || !dateError) {
    throw new Error("Booking form markup is incomplete");
}

console.log("Part A Complete: JavaScript toolchain and DOM variables loaded safely!");

// Part B: Pure, isolated date validation logic function

function validateWeekday(dateText) {
    // 1. Structural check: Must match YYYY-MM-DD format exactly
    if (!/^\d{4}-\d{2}-\d{2}$/.test(dateText)) {
        return "Enter a valid date.";
    }
    
    // 2. Parse into a timezone-safe JavaScript Date object (forcing noon local time)
    const value = new Date(`${dateText}T12:00:00`);
    if (Number.isNaN(value.valueOf())) {
        return "Enter a real date.";
    }
    
    // 3. Inspect day values (0 = Sunday, 6 = Saturday)
    if (value.getDay() === 0 || value.getDay() === 6) {
        return "Choose a weekday, Monday to Friday.";
    }
    
    return ""; // Empty string indicates the input value is valid!
}

// Inline Unit Tests via Console Assertions (The 5 cases from Pre-Lab)
const testCases = [
    ["", "Enter a valid date."],
    ["not-a-date", "Enter a valid date."],
    ["2026-09-19", "Choose a weekday, Monday to Friday."], // This is a Saturday
    ["2026-09-20", "Choose a weekday, Monday to Friday."], // This is a Sunday
    ["2026-09-21", ""] // This is today, Monday (Valid)
];

// Run the assertions loop
for (const [input, expected] of testCases) {
    console.assert(validateWeekday(input) === expected, `Test failed for input value: ${input}`);
}

console.log("Part B Complete: Automated validation checks executed successfully!");

//Part C: Connect the rule to the user interface
// =========================================================================

function showDateError(message) {
    dateError.textContent = message;
    if (message) {
        date.setAttribute("aria-invalid", "true");
    } else {
        date.removeAttribute("aria-invalid");
    }
}

// Clear visual and screen reader error flags instantly when the user fixes the text field
date.addEventListener("input", () => showDateError(""));

// Intercept form transmission boundaries
form.addEventListener("submit", (event) => {
    const message = validateWeekday(date.value);
    showDateError(message);
    
    if (message) {
        event.preventDefault(); // Stop data transit to server handler
        date.focus();           // Immediately push interface focus point to the problematic field
    }
});

console.log("Part C Complete: Interface boundaries connected safely!");