// JavaScript for Animations (if needed beyond CSS)

document.addEventListener('DOMContentLoaded', () => {
    console.log('Animations JS Loaded');
    // Initialize any JS-driven animations here
    // Example: Staggered list item loading
    // applyStaggeredAnimation('.task-list .task-card', 'slide-up', 0.1);
});

/**
 * Applies a staggered animation to a list of elements.
 * @param {string} selector CSS selector for the elements to animate.
 * @param {string} animationClass CSS class containing the animation keyframes.
 * @param {number} delayIncrement Delay increment between each item in seconds.
 */
function applyStaggeredAnimation(selector, animationClass, delayIncrement) {
    const elements = document.querySelectorAll(selector);
    elements.forEach((element, index) => {
        element.style.animationDelay = `${index * delayIncrement}s`;
        element.classList.add(animationClass); // Add the animation class
        // Optional: Remove animation class after it finishes to allow re-triggering if needed
        // element.addEventListener('animationend', () => {
        //     element.classList.remove(animationClass);
        //     element.style.animationDelay = ''; // Reset delay
        // }, { once: true });
    });
}

// Add other JS animation functions as needed
