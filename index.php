<?php
require_once 'includes/functions.php';

// Check if user is logged in
if (isLoggedIn()) {
    // If logged in, redirect to the dashboard
    redirect('dashboard.php');
    exit; // Important to prevent further script execution after redirect
}

// If not logged in, display the landing page content below
$pageTitle = "ToDo App - Organize Your Life"; // Updated Title

// Include the unified header
require_once 'includes/header.php';
?>

    <!-- Hero section -->
    <section class="hero-section">
        <div class="container">
            <h1>To-Do. Simplified.</h1>
            <p class="subtitle">Focus on what matters most with our elegantly designed task management solution.</p>
             <div class="cta-buttons">
                 <a href="register.php" class="button cta-button">Get Started Free</a>
                 <!-- Removed secondary sign-in button from hero, it's in the header -->
             </div>
        </div>
    </section>

    <!-- Main content area (can hold features later if desired) -->
    <main class="container landing-container">
        <!-- Placeholder for potential future content like features -->
         <!-- Example: Add a small introductory text if needed -->
         <!-- <p style="text-align: center; margin-bottom: 40px;">Welcome to your new favorite ToDo App!</p> -->
    </main>

    <!-- Features section (Optional - adapted from example) -->
    <section class="features">
        <div class="container"> <!-- Added container for padding -->
            <h2>Simply Powerful</h2>
            <div class="feature-grid">
                <div class="feature">
                    <div class="feature-icon">
                         <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <h3>Quick Add</h3>
                    <p>Add tasks in seconds with our streamlined interface designed for speed and efficiency.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <h3>Instant Progress</h3>
                    <p>Mark tasks complete with a single click and feel the satisfaction of getting things done.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                         <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                    </div>
                    <h3>Beautiful Design</h3>
                    <p>Enjoy a thoughtfully crafted experience with attention to every detail, inspired by Apple design.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA section (Adapted from example) -->
    <section class="cta">
         <div class="container"> <!-- Added container for padding -->
            <h2>Start Organizing Today</h2>
            <p>Experience a new level of productivity with our beautifully simple to-do list app.</p>
            <a href="register.php" class="button cta-button">Create Account</a>
        </div>
    </section>

<?php
// Include the unified footer
require_once 'includes/footer.php';
?>
