// Main JavaScript file for To-Do List App
// Contains general site-wide scripts, initializations, helper functions

document.addEventListener('DOMContentLoaded', () => {
    console.log('Main JS Loaded');
    initializeAuthForms();
    initializeHeaderScrollEffect(); // Initialize header effect
});

// Example helper function (can be expanded)
function showMessage(elementId, message, type = 'info') {
    const messageElement = document.getElementById(elementId);
    if (messageElement) {
        messageElement.textContent = message;
        // Add classes for styling (e.g., .message.error, .message.success)
        // Ensure base 'message' class is always present
        messageElement.className = 'message'; // Reset classes
        if (type === 'error') {
            messageElement.classList.add('error');
        } else if (type === 'success') {
            messageElement.classList.add('success');
        } else {
             messageElement.classList.add('info');
        }
        messageElement.style.display = 'block'; // Make it visible
    } else {
        // Fallback if specific element doesn't exist
        console.log(`[${type.toUpperCase()}] ${message}`);
        // Example: alert(`[${type.toUpperCase()}] ${message}`);
    }
}

// --- Authentication Form Handling ---

function initializeAuthForms() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    loginForm?.addEventListener('submit', handleLogin);
    registerForm?.addEventListener('submit', handleRegister);
}

async function handleLogin(event) {
    event.preventDefault(); // Prevent default form submission
    const form = event.target;
    const formData = new FormData(form);
    const messageElementId = 'login-message'; // ID of the div to show messages
    const submitButton = form.querySelector('button[type="submit"]');

    showMessage(messageElementId, 'Logging in...', 'info');
    if(submitButton) {
        submitButton.disabled = true;
        submitButton.classList.add('loading'); // Add loading class
    }

    try {
        const response = await fetch('api/auth/login.php', {
            method: 'POST',
            body: formData
        });

        // Clear message area before processing response
        // showMessage(messageElementId, '', 'info'); // Clearing is handled by the next showMessage call

        if (!response.ok) {
             // Try to get error message from response body if possible
            let errorMsg = `HTTP error! status: ${response.status}`;
            try {
                const errorData = await response.json();
                errorMsg = errorData.message || errorMsg;
            } catch(e) { /* Ignore if response is not JSON */ }
            throw new Error(errorMsg);
        }

        const result = await response.json();

        if (result.success) {
            showMessage(messageElementId, result.message, 'success');
            // Redirect to dashboard after a short delay
            setTimeout(() => {
                 window.location.href = 'dashboard.php'; // Or use result.redirect if provided
            }, 1000); // 1 second delay
        } else {
            showMessage(messageElementId, result.message || 'Login failed.', 'error');
            if(submitButton) submitButton.disabled = false; // Re-enable button on failure
        }

    } catch (error) {
        console.error('Login error:', error);
        showMessage(messageElementId, `Login failed: ${error.message}. Please try again.`, 'error');
        if(submitButton) {
            submitButton.disabled = false;
            submitButton.classList.remove('loading'); // Remove loading class
        }
    } finally {
         // Ensure button is re-enabled and loading class removed even if unexpected error occurs
         if(submitButton && submitButton.disabled) {
             submitButton.disabled = false;
             submitButton.classList.remove('loading');
         }
    }
}

// --- Header Scroll Effect ---

function initializeHeaderScrollEffect() {
    const header = document.querySelector('.dashboard-header');
    if (!header) return; // Only run if header exists

    window.addEventListener('scroll', () => {
        // Add 'scrolled' class if page is scrolled down, remove if at top
        if (window.scrollY > 10) { // Add class after scrolling down a bit (e.g., 10px)
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }, { passive: true }); // Use passive listener for better scroll performance
}

async function handleRegister(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const messageElementId = 'register-message';
    const submitButton = form.querySelector('button[type="submit"]');

    // Basic client-side validation (example: password match)
    const password = formData.get('password');
    const confirmPassword = formData.get('confirm_password');
    if (password !== confirmPassword) {
        showMessage(messageElementId, 'Passwords do not match.', 'error');
        return; // Stop submission
    }
     if (password.length < 6) {
        showMessage(messageElementId, 'Password must be at least 6 characters long.', 'error');
        return;
    }

    showMessage(messageElementId, 'Registering...', 'info');
     if(submitButton) {
         submitButton.disabled = true;
         submitButton.classList.add('loading'); // Add loading class
     }

    try {
        const response = await fetch('api/auth/register.php', {
            method: 'POST',
            body: formData
        });

        // Clear message area before processing response
        // showMessage(messageElementId, '', 'info'); // Clearing is handled by the next showMessage call

        if (!response.ok) {
            let errorMsg = `HTTP error! status: ${response.status}`;
            try {
                const errorData = await response.json();
                errorMsg = errorData.message || errorMsg;
            } catch(e) { /* Ignore if response is not JSON */ }
            throw new Error(errorMsg);
        }

        const result = await response.json();

        if (result.success) {
            showMessage(messageElementId, result.message, 'success');
            // Optionally redirect to login page after a delay or clear form
            setTimeout(() => {
                 window.location.href = 'login.php';
            }, 2000); // Redirect after 2 seconds
        } else {
            showMessage(messageElementId, result.message || 'Registration failed.', 'error');
             if(submitButton) submitButton.disabled = false; // Re-enable button
        }

    } catch (error) {
        console.error('Registration error:', error);
        showMessage(messageElementId, `Registration failed: ${error.message}. Please try again.`, 'error');
        if(submitButton) {
            submitButton.disabled = false;
            submitButton.classList.remove('loading'); // Remove loading class
        }
    } finally {
        // Ensure button is re-enabled and loading class removed
        if(submitButton && submitButton.disabled) {
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
        }
    }
}
