<?php
// lang/id.php - Indonesian Translations

return [
    // Header
    'app_name' => 'Aplikasi To-Do',
    'login_link' => 'Masuk',
    'register_button' => 'Daftar',
    'welcome_message' => 'Selamat datang, %s!', // %s is placeholder for username
    'logout_link' => 'Keluar',

    // Add more keys as needed for other pages/elements
    'page_title_default' => 'Aplikasi To-Do',
    'page_title_dashboard' => 'Dasbor',
    'page_title_login' => 'Masuk',
    'page_title_register' => 'Daftar',
    'page_title_index' => 'Aplikasi ToDo - Atur Hidup Anda',

    // Index Page (Landing)
    'index_hero_title' => 'To-Do. Disederhanakan.',
    'index_hero_subtitle' => 'Fokus pada hal terpenting dengan solusi manajemen tugas kami yang dirancang elegan.',
    'index_hero_cta' => 'Mulai Gratis',
    'index_features_title' => 'Sederhana Namun Kuat',
    'index_feature1_title' => 'Tambah Cepat',
    'index_feature1_desc' => 'Tambahkan tugas dalam hitungan detik dengan antarmuka kami yang efisien dan dirancang untuk kecepatan.',
    'index_feature2_title' => 'Progres Instan',
    'index_feature2_desc' => 'Tandai tugas selesai dengan sekali klik dan rasakan kepuasan menyelesaikan pekerjaan.',
    'index_feature3_title' => 'Desain Indah',
    'index_feature3_desc' => 'Nikmati pengalaman yang dibuat dengan cermat dengan perhatian pada setiap detail, terinspirasi oleh desain Apple.',
    'index_cta_title' => 'Mulai Mengatur Hari Ini',
    'index_cta_subtitle' => 'Rasakan tingkat produktivitas baru dengan aplikasi daftar tugas kami yang indah dan sederhana.',
    'index_cta_button' => 'Buat Akun',

    // Login Page
    'login_heading' => 'Masuk',
    'login_label_username' => 'Nama Pengguna atau Email',
    'login_label_password' => 'Kata Sandi',
    'login_button_submit' => 'Masuk',
    'login_switch_text' => 'Belum punya akun?',
    'login_switch_link' => 'Daftar',

    // Register Page
    'register_heading' => 'Buat Akun',
    'register_label_username' => 'Nama Pengguna',
    'register_label_email' => 'Email',
    'register_label_password' => 'Kata Sandi',
    'register_label_confirm_password' => 'Konfirmasi Kata Sandi',
    'register_button_submit' => 'Daftar',
    'register_switch_text' => 'Sudah punya akun?',
    'register_switch_link' => 'Masuk',

    // Dashboard Page (Static PHP parts)
    'dashboard_sidebar_view' => 'Tampilan',
    'dashboard_sidebar_all_tasks' => 'Semua Tugas',
    'dashboard_sidebar_pending' => 'Tertunda',
    'dashboard_sidebar_completed' => 'Selesai',
    'dashboard_sidebar_search' => 'Cari',
    'dashboard_sidebar_search_placeholder' => 'Cari tugas...',
    'dashboard_sidebar_stats' => 'Statistik',
    'dashboard_sidebar_stats_total' => 'Total:',
    'dashboard_sidebar_stats_pending' => 'Tertunda:',
    'dashboard_sidebar_stats_completed' => 'Selesai:',
    'dashboard_add_task_title' => 'Tambah Tugas Baru',
    'dashboard_add_task_placeholder_title' => 'Judul Tugas',
    'dashboard_add_task_placeholder_desc' => 'Deskripsi (Opsional)',
    'dashboard_add_task_button' => 'Tambah Tugas',
    'dashboard_task_list_title' => 'Tugas Anda',
    'dashboard_edit_modal_title' => 'Edit Tugas',
    'dashboard_edit_modal_label_title' => 'Judul:',
    'dashboard_edit_modal_label_desc' => 'Deskripsi:',
    'dashboard_edit_modal_label_priority' => 'Prioritas:',
    'dashboard_edit_modal_priority_low' => 'Rendah',
    'dashboard_edit_modal_priority_medium' => 'Sedang',
    'dashboard_edit_modal_priority_high' => 'Tinggi',
    'dashboard_edit_modal_label_due_date' => 'Tanggal Jatuh Tempo:',
    'dashboard_edit_modal_label_status' => 'Status:',
    'dashboard_edit_modal_status_pending' => 'Tertunda',
    'dashboard_edit_modal_status_completed' => 'Selesai',
    'dashboard_edit_modal_button_save' => 'Simpan Perubahan',

    // JavaScript Translations (tasks.js & potentially main.js)
    'js_loading_tasks' => 'Memuat tugas...',
    'js_auth_error_load' => 'Kesalahan autentikasi saat memuat tugas.',
    'js_auth_error_login' => 'Kesalahan autentikasi. Silakan masuk lagi.',
    'js_http_error' => 'Kesalahan HTTP! Status: %s', // %s for status code
    'js_api_error_load' => 'Kesalahan API saat memuat tugas: %s', // %s for message
    'js_fail_load_tasks' => 'Gagal memuat tugas: %s', // %s for message
    'js_error_load_tasks' => 'Terjadi kesalahan saat memuat tugas: %s.', // %s for message
    'js_no_tasks_yet' => 'Belum ada tugas',
    'js_no_tasks_prompt' => 'Klik "Tambah Tugas Baru" atau tekan "/" untuk membuat tugas pertama Anda',
    'js_mark_as_pending' => 'Tandai sebagai Tertunda',
    'js_mark_as_completed' => 'Tandai sebagai Selesai',
    'js_edit_task_title' => 'Edit Tugas',
    'js_delete_task_title' => 'Hapus Tugas',
    'js_task_title_empty' => 'Judul tugas tidak boleh kosong.',
    'js_fail_add_task' => 'Gagal menambahkan tugas: %s', // %s for message
    'js_error_add_task' => 'Terjadi kesalahan saat menambahkan tugas: %s.', // %s for message
    'js_confirm_delete_task' => 'Apakah Anda yakin ingin menghapus tugas ini?',
    'js_fail_delete_task' => 'Gagal menghapus tugas: %s', // %s for message
    'js_error_delete_task' => 'Terjadi kesalahan saat menghapus tugas: %s.', // %s for message
    'js_fail_update_status' => 'Gagal memperbarui status tugas: %s', // %s for message
    'js_error_update_status' => 'Terjadi kesalahan saat memperbarui status tugas: %s.', // %s for message
    'js_edit_task_not_found' => 'Tidak dapat menemukan data tugas untuk diedit.',
    'js_saving_changes' => 'Menyimpan perubahan...',
    'js_fail_update_task' => 'Gagal memperbarui tugas: %s', // %s for message
    'js_error_update_task' => 'Terjadi kesalahan saat memperbarui tugas: %s.', // %s for message
    'js_untitled_task' => 'Tugas Tanpa Judul',
    // Add keys for main.js if needed (e.g., for showMessage types)
    'js_message_type_success' => 'Sukses',
    'js_message_type_error' => 'Kesalahan',
    'js_message_type_info' => 'Info',

    // Footer
    'footer_copyright' => 'Hak Cipta © %d Adrian. Semua hak dilindungi undang-undang.', // %d for year

    // JavaScript Translations (main.js)
    'js_logging_in' => 'Sedang masuk...',
    'js_login_failed_default' => 'Gagal masuk.',
    'js_login_failed_error' => 'Gagal masuk: %s. Silakan coba lagi.', // %s for error message
    'js_registering' => 'Mendaftar...',
    'js_passwords_no_match' => 'Kata sandi tidak cocok.',
    'js_password_too_short' => 'Kata sandi minimal harus 6 karakter.',
    'js_registration_failed_default' => 'Pendaftaran gagal.',
    'js_registration_failed_error' => 'Pendaftaran gagal: %s. Silakan coba lagi.', // %s for error message

];
