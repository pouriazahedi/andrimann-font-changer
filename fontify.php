<?php
/*
Plugin Name: Fontify
Description: Upload and apply custom fonts (woff, woff2) across your WordPress site and admin panel.
Version: 1.0.0
Author: Pouria Zahedi
Author URI: https://github.com/pouriazahedi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: fontify
Domain Path: /languages
*/

if (!defined('ABSPATH')) exit;

// Add a menu item in the admin dashboard
add_action('admin_menu', function () {
    add_menu_page(
        esc_html__('Custom Font', 'fontify'),
        esc_html__('Custom Font', 'fontify'),
        'manage_options',
        'fontify',
        'wpfm_settings_page'
    );
});

// Render the settings page
function wpfm_settings_page()
{
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!isset($_POST['wpfm_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wpfm_nonce'])), 'wpfm_font_upload_nonce')) {
            echo '<div class="notice notice-error"><p>' . esc_html__('Security check failed.', 'fontify') . '</p></div>';
        } else {

            // Save font name
            if (isset($_POST['wpfm_font_name'])) {
                update_option('wpfm_font_name', sanitize_text_field(wp_unslash($_POST['wpfm_font_name'])));
            }

            // Handle file upload
            if (!empty($_FILES['wpfm_font_file']['name'])) {
                require_once ABSPATH . 'wp-admin/includes/file.php';

                $upload_dir = wp_upload_dir();
                $custom_dir = $upload_dir['basedir'] . '/custom-fonts';

                if (!file_exists($custom_dir)) {
                    wp_mkdir_p($custom_dir);
                }

                $allowed_types = ['woff', 'woff2'];
                $file_type = pathinfo(sanitize_file_name($_FILES['wpfm_font_file']['name']), PATHINFO_EXTENSION);

                if (in_array(strtolower($file_type), $allowed_types)) {
                    $upload_overrides = ['test_form' => false];
                    $movefile = wp_handle_upload($_FILES['wpfm_font_file'], $upload_overrides);

                    if ($movefile && !isset($movefile['error'])) {
                        update_option('wpfm_font_file', esc_url_raw($movefile['url']));
                        echo '<div class="notice notice-success"><p>' . esc_html__('Font uploaded successfully.', 'fontify') . '</p></div>';
                    } else {
                        echo '<div class="notice notice-error"><p>' . sprintf(esc_html__('Error uploading file: %s', 'fontify'), esc_html($movefile['error'])) . '</p></div>';
                    }
                } else {
                    echo '<div class="notice notice-error"><p>' . esc_html__('Only woff and woff2 formats are supported.', 'fontify') . '</p></div>';
                }
            }
        }
    }

    // Load saved options
    $font_name = get_option('wpfm_font_name', '');
    $font_file = get_option('wpfm_font_file', '');

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Custom Font Settings', 'fontify'); ?></h1>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('wpfm_font_upload_nonce', 'wpfm_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Font Name (font-family)', 'fontify'); ?></th>
                    <td><input type="text" name="wpfm_font_name" value="<?php echo esc_attr($font_name); ?>" required></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Font File (woff or woff2)', 'fontify'); ?></th>
                    <td><input type="file" name="wpfm_font_file" accept=".woff,.woff2"></td>
                </tr>
            </table>
            <?php submit_button(esc_html__('Save Settings', 'fontify')); ?>
        </form>
        <?php if ($font_name && $font_file): ?>
            <h2><?php esc_html_e('Preview', 'fontify'); ?></h2>
            <p style="font-family: '<?php echo esc_attr($font_name); ?>'; font-size: 20px;">
                <?php esc_html_e('This is a preview using your uploaded font.', 'fontify'); ?>
            </p>
        <?php endif; ?>
    </div>
    <?php
}

// Enqueue custom font for both frontend and admin
function wpfm_enqueue_custom_font()
{
    $font_name = get_option('wpfm_font_name', '');
    $font_file = get_option('wpfm_font_file', '');

    if ($font_name && $font_file) {
        $font_face = "@font-face {
            font-family: '" . esc_attr($font_name) . "';
            src: url('" . esc_url($font_file) . "') format('woff2');
            font-display: swap;
        }
        body, input, textarea, select, button {
            font-family: '" . esc_attr($font_name) . "', sans-serif !important;
        }";

        wp_add_inline_style('wp-block-library', $font_face);
    }
}
add_action('admin_enqueue_scripts', 'wpfm_enqueue_custom_font');
add_action('wp_enqueue_scripts', 'wpfm_enqueue_custom_font');