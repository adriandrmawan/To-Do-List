<footer class="<?php echo isset($_SESSION['user_id']) ? 'dashboard-footer' : 'public-footer'; ?>">
        <div class="container">
            <div class="divider"></div>
            <p>Copyright © <?php echo date('Y'); ?> Adrian. All rights reserved.</p>
        </div>
    </footer>

    <!-- Core JavaScript Files -->
    <script src="assets/js/main.js"></script>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Authenticated user scripts -->
        <script src="assets/js/tasks.js"></script>
        <script src="assets/js/animations.js"></script>
    <?php endif; ?>
</body>
</html>
