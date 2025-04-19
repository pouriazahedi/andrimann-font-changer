<?php
/*
Plugin Name: Custom Font Loader
Description: افزونه‌ای برای آپلود و استفاده از فونت دلخواه در سایت و پیشخوان وردپرس.
Version: 1.0
Author: Pouria Zahedi
*/

if (!defined('ABSPATH')) exit;

// منوی تنظیمات
add_action('admin_menu', function () {
    add_menu_page('فونت دلخواه', 'فونت دلخواه', 'manage_options', 'custom-font-loader', 'cfl_settings_page');
});

// صفحه تنظیمات
function cfl_settings_page()
{
    if (isset($_POST['cfl_font_name'])) {
        update_option('cfl_font_name', sanitize_text_field($_POST['cfl_font_name']));
    }

    // آپلود فایل
    if (!empty($_FILES['cfl_font_file']['name'])) {
        $upload_dir = wp_upload_dir();
        $custom_dir = $upload_dir['basedir'] . '/custom-fonts';

        if (!file_exists($custom_dir)) {
            wp_mkdir_p($custom_dir);
        }

        $allowed_types = ['woff', 'woff2'];
        $file_type = pathinfo($_FILES['cfl_font_file']['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($file_type), $allowed_types)) {
            $filename = sanitize_file_name($_FILES['cfl_font_file']['name']);
            $target = $custom_dir . '/' . $filename;

            if (move_uploaded_file($_FILES['cfl_font_file']['tmp_name'], $target)) {
                update_option('cfl_font_file', $filename);
                echo '<div class="notice notice-success"><p>فونت با موفقیت آپلود شد.</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>خطا در آپلود فایل.</p></div>';
            }
        } else {
            echo '<div class="notice notice-error"><p>فقط فرمت‌های woff و woff2 پشتیبانی می‌شوند.</p></div>';
        }
    }

    $font_name = get_option('cfl_font_name', '');
    $font_file = get_option('cfl_font_file', '');
    ?>

    <div class="wrap">
        <h1>تنظیم فونت دلخواه</h1>
        <form method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <th scope="row">نام فونت (font-family)</th>
                    <td><input type="text" name="cfl_font_name" value="<?php echo esc_attr($font_name); ?>" required></td>
                </tr>
                <tr>
                    <th scope="row">فایل فونت (woff یا woff2)</th>
                    <td><input type="file" name="cfl_font_file" accept=".woff,.woff2"></td>
                </tr>
            </table>
            <?php submit_button('ذخیره تنظیمات'); ?>
        </form>
    </div>
    <?php
}

// افزودن فونت به بخش فرانت و ادمین
function cfl_enqueue_custom_font()
{
    $font_name = get_option('cfl_font_name', '');
    $font_file = get_option('cfl_font_file', '');

    if ($font_name && $font_file) {
        $upload_dir = wp_upload_dir();
        $font_url = $upload_dir['baseurl'] . '/custom-fonts/' . $font_file;

        $font_face = "@font-face {
            font-family: '{$font_name}';
            src: url('{$font_url}') format('woff2');
            font-display: swap;
        }";

        $apply_font = "body, html, * {
            font-family: '{$font_name}', sans-serif !important;
        }";

        wp_register_style('cfl-font-style', false);
        wp_enqueue_style('cfl-font-style');
        wp_add_inline_style('cfl-font-style', $font_face . $apply_font);
    }
}
add_action('wp_enqueue_scripts', 'cfl_enqueue_custom_font');
add_action('admin_enqueue_scripts', 'cfl_enqueue_custom_font');