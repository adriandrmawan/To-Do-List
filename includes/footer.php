<footer class="<?php echo isset($_SESSION['user_id']) ? 'dashboard-footer' : 'public-footer'; ?>">
        <div class="container">
            <div class="divider"></div>
            <p><?php echo t('footer_copyright', date('Y')); ?></p>
        </div>
    </footer>

    <!-- Translations for JavaScript -->
    <script>
        window.translations = <?php echo json_encode($translations ?? []); ?>;

        /**
         * JavaScript translation function.
         * Uses the window.translations object populated by PHP.
         * Supports sprintf-style argument substitution.
         *
         * @param {string} key The translation key.
         * @param {...any} args Optional arguments for substitution.
         * @returns {string} The translated string or the key itself.
         */
        function t_js(key, ...args) {
            let text = window.translations && window.translations[key] ? window.translations[key] : key;
            if (args.length > 0 && typeof text === 'string') {
                // Basic sprintf implementation for %s
                let i = 0;
                text = text.replace(/%s/g, () => args[i++]);
                // Add more format specifiers (%d, etc.) if needed
            }
            return text;
        }
    </script>

    <!-- Core JavaScript Files -->
    <script src="assets/js/main.js"></script>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Authenticated user scripts -->
        <script src="assets/js/tasks.js"></script>
        <script src="assets/js/animations.js"></script>
    <?php endif; ?>
</body>
</html>
