<?php

if (!defined('ABSPATH')) {
    exit;
}

class BBMWPV_Bulk_Download
{

    /**
     * Initialize hooks.
     *
     * @return void
     */
    public function init()
    {

        add_action(
            'wp_ajax_bbmwpv_bulk_download',
            array($this, 'handle_bulk_download')
        );

        add_action(
            'admin_post_bbmwpv_download_bundle',
            array($this, 'download_bundle')
        );
    }

    /**
     * Handle bulk download.
     *
     * @return void
     */
    public function handle_bulk_download()
    {

        if (!current_user_can('manage_options')) {

            wp_send_json_error(
                array(
                    'message' => 'Permission denied.',
                )
            );
        }

        check_ajax_referer(
            'bbmwpv_nonce',
            'nonce'
        );

        $backup_ids = isset($_POST['files'])
        ? array_map('sanitize_text_field', (array) wp_unslash($_POST['files']))
        : array();

        if (empty($backup_ids)) {

            wp_send_json_error(
                array(
                    'message' => 'No backups selected.',
                )
            );
        }

        $backup_dir = WP_CONTENT_DIR . '/wpvividbackups';

        if (!is_dir($backup_dir)) {

            wp_send_json_error(
                array(
                    'message' => 'Backup directory not found.',
                )
            );
        }

        $all_files = scandir($backup_dir);

        if (false === $all_files) {

            wp_send_json_error(
                array(
                    'message' => 'Failed to scan backup directory.',
                )
            );
        }

        $matched_files = array();

        foreach ($all_files as $file) {

            if ('.' === $file || '..' === $file) {
                continue;
            }

            foreach ($backup_ids as $backup_id) {

                if (false !== strpos($file, $backup_id)) {

                    $matched_files[] = $file;

                    break;
                }
            }
        }

        if (empty($matched_files)) {

            wp_send_json_error(
                array(
                    'message' => 'No matching backup files found.',
                )
            );
        }

        if (!class_exists('ZipArchive')) {

            wp_send_json_error(
                array(
                    'message' => 'ZipArchive is not available.',
                )
            );
        }

        $site_url = home_url();

/**
 * Remove protocol.
 */
        $site_url = preg_replace(
            '#^https?://#',
            '',
            $site_url
        );

/**
 * Convert unsafe filename chars.
 */
        $site_url = str_replace(
            array(
                '/',
                '\\',
                ':',
                '?',
                '&',
                '=',
                '.',
            ),
            array(
                '-',
                '-',
                '-',
                '-',
                '-',
                '-',
                '-',
            ),
            $site_url
        );

/**
 * Cleanup duplicate dashes.
 */
        $site_url = preg_replace(
            '/-+/',
            '-',
            $site_url
        );

        $site_url = trim(
            $site_url,
            '-'
        );

        $date = wp_date(
            'Y-m-d-His'
        );

        $zip_filename =
            $site_url .
            '-wpvivid-bundle-' .
            $date .
            '.zip';

        $temp_file = wp_tempnam(
            $zip_filename
        );

        if (false === $temp_file) {

            wp_send_json_error(
                array(
                    'message' => 'Failed to create temporary file.',
                )
            );
        }

/**
 * Rename temp file.
 */
        $zip_path =
            dirname($temp_file) .
            '/' .
            $zip_filename;

        global $wp_filesystem;

        WP_Filesystem();

        $wp_filesystem->move(
            $temp_file,
            $zip_path,
            true
        );

        if (!file_exists($zip_path)) {

            wp_send_json_error(
                array(
                    'message' => 'Failed to move ZIP file.',
                )
            );
        }

        if (false === $zip_path) {

            wp_send_json_error(
                array(
                    'message' => 'Failed to create temporary file.',
                )
            );
        }

        $zip = new ZipArchive();

        if (true !== $zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

            wp_send_json_error(
                array(
                    'message' => 'Failed to create ZIP archive.',
                )
            );
        }

        foreach ($matched_files as $file) {

            $file_path = trailingslashit($backup_dir) . $file;

            if (file_exists($file_path)) {

                $zip->addFile(
                    $file_path,
                    $file
                );
            }
        }

        $zip->close();

        /**
         * Generate download token.
         */
        $token = wp_generate_password(32, false);

        set_transient(
            'bbmwpv_bundle_' . $token,
            $zip_path,
            HOUR_IN_SECONDS
        );

        /**
         * Generate secure download URL.
         */
        $url = add_query_arg(
            array(
                'action' => 'bbmwpv_download_bundle',
                'token' => rawurlencode($token),
                'bbmwpv_nonce' => wp_create_nonce(
                    'bbmwpv_download_bundle'
                ),
            ),
            admin_url('admin-post.php')
        );

        wp_send_json_success(
            array(
                'message' => 'ZIP bundle created.',
                'url' => $url,
            )
        );

    }
    /**
     * Download generated bundle.
     *
     * @return void
     */
    public function download_bundle()
    {

        if (!current_user_can('manage_options')) {
            wp_die('Permission denied.');
        }
        if (
            !isset($_GET['bbmwpv_nonce']) ||
            !wp_verify_nonce(
                sanitize_text_field(
                    wp_unslash($_GET['bbmwpv_nonce'])
                ),
                'bbmwpv_download_bundle'
            )
        ) {
            wp_die('Invalid nonce.');
        }

        $token = isset($_GET['token'])
        ? sanitize_text_field(wp_unslash($_GET['token']))
        : '';

        if (empty($token)) {
            wp_die('Invalid token.');
        }

        $zip_path = get_transient(
            'bbmwpv_bundle_' . $token
        );

        if (
            empty($zip_path) ||
            !file_exists($zip_path)
        ) {
            wp_die('Download file not found.');
        }

        $filename = basename($zip_path);

        header('Content-Type: application/zip');
        header(
            'Content-Disposition: attachment; filename="' .
            $filename .
            '"'
        );
        header(
            'Content-Length: ' . filesize($zip_path)
        );

        header('Pragma: no-cache');
        header('Expires: 0');

        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile -- Required for ZIP stream download.
        readfile($zip_path);

        wp_delete_file($zip_path);

        delete_transient(
            'bbmwpv_bundle_' . $token
        );

        exit;
    }
}
