<?php
// Include necessary files
require_once 'includes/config.php'; // Load config first (for language)
require_once 'includes/functions.php';

// Check if user is logged in
if (isLoggedIn()) {
    // If logged in, redirect to the dashboard
    redirect('dashboard.php');
    exit; // Important to prevent further script execution after redirect
}

// If not logged in, display the landing page content below
$pageTitleKey = "page_title_index"; // Use translation key for title

// Include the unified header
require_once 'includes/header.php';
?>

    <!-- Hero section -->
    <section class="hero-section">
        <div class="container">
            <h1><?php echo t('index_hero_title'); ?></h1>
            <p class="subtitle"><?php echo t('index_hero_subtitle'); ?></p>
             <div class="cta-buttons">
                 <a href="register.php" class="button cta-button"><?php echo t('index_hero_cta'); ?></a>
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
            <h2><?php echo t('index_features_title'); ?></h2>
            <div class="feature-grid">
                <div class="feature">
                    <div class="feature-icon">
                         <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <h3><?php echo t('index_feature1_title'); ?></h3>
                    <p><?php echo t('index_feature1_desc'); ?></p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <h3><?php echo t('index_feature2_title'); ?></h3>
                    <p><?php echo t('index_feature2_desc'); ?></p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                         <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                    </div>
                    <h3><?php echo t('index_feature3_title'); ?></h3>
                    <p><?php echo t('index_feature3_desc'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA section (Adapted from example) -->
    <section class="cta">
         <div class="container"> <!-- Added container for padding -->
            <h2><?php echo t('index_cta_title'); ?></h2>
            <p><?php echo t('index_cta_subtitle'); ?></p>
            <a href="register.php" class="button cta-button"><?php echo t('index_cta_button'); ?></a>
        </div>
    </section>

<?php
// Include the unified footer
require_once 'includes/footer.php';
?>
