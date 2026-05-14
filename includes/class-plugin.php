<?php
/**
 * Main plugin class.
 *
 * @package BulkBackupManagerForWPvivid
 */

if (!defined('ABSPATH')) {
    exit;
}

class BBMWPV_Plugin
{

    /**
     * Initialize plugin.
     *
     * @return void
     */
    public function init()
    {

        $this->load_files();
        $this->init_hooks();
    }

    /**
     * Load required files.
     *
     * @return void
     */
    private function load_files()
    {

        $files = array(
            'includes/class-admin.php',
            'includes/class-bulk-download.php',
            'includes/class-bulk-upload.php',
            'includes/class-zip-handler.php',
            'includes/class-security.php',
            'includes/class-wpvivid-integration.php',
            'includes/functions.php',
        );

        foreach ($files as $file) {

            $path = BBMWPV_PLUGIN_PATH . $file;

            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    /**
     * Initialize hooks/classes.
     *
     * @return void
     */
    private function init_hooks()
    {

        if (class_exists('BBMWPV_Admin')) {

            $admin = new BBMWPV_Admin();
            $admin->init();
        }

        if (class_exists('BBMWPV_Bulk_Download')) {

            $bulk_download = new BBMWPV_Bulk_Download();
            $bulk_download->init();
        }

        if (class_exists('BBMWPV_Bulk_Upload')) {

            $bulk_upload = new BBMWPV_Bulk_Upload();
            $bulk_upload->init();
        }

        if (class_exists('BBMWPV_Security')) {

            $security = new BBMWPV_Security();
            $security->init();
        }

        if (class_exists('BBMWPV_WPvivid_Integration')) {

            $integration = new BBMWPV_WPvivid_Integration();
            $integration->init();
        }
    }
}
