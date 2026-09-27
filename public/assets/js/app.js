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

const slotStatus = document.querySelector("#slot-status");
const slotList = document.querySelector("#slots");
let activeController;

// Safely inserts new options using textContent to avoid XSS injections
function renderSlots(slots) {
    slotList.replaceChildren(); // Clears previous options
    
    // Add default blank option
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Choose a time";
    slotList.append(defaultOption);

    for (const slot of slots) {
        if (!Number.isInteger(slot.id) || typeof slot.label !== "string") continue;
        const option = document.createElement("option");
        option.value = String(slot.id);
        option.textContent = slot.label;
        slotList.append(option);
    }
}

async function loadSlots(serviceId) {
    // Abort any ongoing previous fetch requests to prevent stale race conditions
    activeController?.abort();
    activeController = new AbortController();

    slotStatus.textContent = "Loading available times...";
    slotList.replaceChildren();

    try {
        const url = `/api/slots.php?serviceId=${encodeURIComponent(serviceId)}`;
        const response = await fetch(url, {
            headers: { Accept: "application/json" },
            signal: activeController.signal
        });

        if (!response.ok) throw new Error(`Request failed: ${response.status}`);

        const slots = await response.json();
        if (!Array.isArray(slots)) throw new TypeError("Unexpected response shape");

        renderSlots(slots);
        
        // Update user status
        slotStatus.textContent = slots.length
            ? `${slots.length} times available.`
            : "No times are available.";

    } catch (error) {
        if (error.name !== "AbortError") {
            slotStatus.textContent = "Times could not be loaded. Try again.";
            console.error(error);
        }
    }
}

// Fire the network request whenever the selected service changes
service.addEventListener("change", () => {
    if (service.value) {
        loadSlots(service.value);
    } else {
        slotStatus.textContent = "Choose a service to see available times.";
        slotList.replaceChildren();
    }
});
