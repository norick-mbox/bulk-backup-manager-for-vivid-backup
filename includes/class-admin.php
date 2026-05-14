<?php
/**
 * Admin class.
 *
 * @package BulkBackupManagerForWPvivid
 */

if (!defined('ABSPATH')) {
    exit;
}

class BBMWPV_Admin
{

    /**
     * Initialize hooks.
     *
     * @return void
     */
    public function init()
    {

        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));

        add_action('admin_footer', array($this, 'inject_bulk_button'));
    }

    /**
     * Enqueue admin assets.
     *
     * @param string $hook Current admin hook.
     * @return void
     */
    public function enqueue_assets($hook)
    {

        if (!$this->is_wpvivid_screen()) {
            return;
        }

        wp_enqueue_style(
            'bbmwpv-admin',
            BBMWPV_PLUGIN_URL . 'admin/css/admin.css',
            array(),
            BBMWPV_VERSION
        );

        wp_enqueue_script(
            'bbmwpv-admin',
            BBMWPV_PLUGIN_URL . 'admin/js/admin.js',
            array('jquery'),
            BBMWPV_VERSION,
            true
        );

        wp_localize_script(
            'bbmwpv-admin',
            'bbmwpv',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('bbmwpv_nonce'),
            )
        );
    }

    /**
     * Inject bulk download button.
     *
     * @return void
     */
    public function inject_bulk_button()
    {

        if (!$this->is_wpvivid_screen()) {
            return;
        }

        include BBMWPV_PLUGIN_PATH . 'admin/views/bulk-download-button.php';
    }

    /**
     * Check WPvivid admin screen.
     *
     * @return bool
     */
    private function is_wpvivid_screen()
    {

        if (!function_exists('get_current_screen')) {
            return false;
        }

        $screen = get_current_screen();

        if (empty($screen->id)) {
            return false;
        }

        return false !== stripos($screen->id, 'wpvivid');

    }
}
