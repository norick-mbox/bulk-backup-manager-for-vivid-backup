<?php
/**
 * Bulk upload class.
 *
 * @package NoriviveBackupToolsForVividBackup
 */

if (!defined('ABSPATH')) {
    exit;
}

class BBMWPV_Bulk_Upload
{

    /**
     * Initialize hooks.
     *
     * @return void
     */
    public function init()
    {

        add_action(
            'wp_ajax_bbmwpv_bulk_upload',
            array($this, 'handle_bulk_upload')
        );
    }

    /**
     * Handle bulk upload.
     *
     * @return void
     */
    public function handle_bulk_upload()
    {

        if (!current_user_can('manage_options')) {

            wp_send_json_error(
                array(
                    'message' => __('Permission denied.', 'norivive-backup-tools-for-vivid-backup'),
                ),
                403
            );
        }

        check_ajax_referer('bbmwpv_nonce', 'nonce');

        $bbmwpv_uploaded_file =
        isset($_FILES['file'])
        ? wp_unslash($_FILES['file'])
        : array();

        if (
            empty($bbmwpv_uploaded_file) ||
            empty($bbmwpv_uploaded_file['name'])
        ) {

            wp_send_json_error(
                array(
                    'message' => __('No file uploaded.', 'norivive-backup-tools-for-vivid-backup'),
                ),
                400
            );
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';

        $uploaded_file = wp_unslash($_FILES['file']);

        $overrides = array(
            'test_form' => false,
            'mimes' => array(
                'zip' => 'application/zip',
            ),
        );

        $movefile = wp_handle_upload($uploaded_file, $overrides);

        if (isset($movefile['error'])) {

            wp_send_json_error(
                array(
                    'message' => sanitize_text_field($movefile['error']),
                ),
                400
            );
        }

        $zip_path = $movefile['file'];

        $extract_dir = trailingslashit(
            wp_upload_dir()['basedir']
        ) . 'bbmwpv-import/' . wp_generate_password(12, false);

        wp_mkdir_p($extract_dir);

        $zip = new ZipArchive();

        if (true !== $zip->open($zip_path)) {

            wp_send_json_error(
                array(
                    'message' => __('Failed to open ZIP file.', 'norivive-backup-tools-for-vivid-backup'),
                ),
                500
            );
        }

        /**
         * Basic ZIP bomb protection.
         */
        if ($zip->numFiles > 1000) {

            $zip->close();

            wp_send_json_error(
                array(
                    'message' => __('Too many files in ZIP archive.', 'norivive-backup-tools-for-vivid-backup'),
                ),
                400
            );
        }

        $zip->extractTo($extract_dir);

        $zip->close();

        $backup_dir = WP_CONTENT_DIR . '/wpvividbackups/';

        $imported = array();

        $files = scandir($extract_dir);

        if (false === $files) {
            $files = array();
        }

        foreach ($files as $file) {

            if ('.' === $file || '..' === $file) {
                continue;
            }

            $source = trailingslashit($extract_dir) . $file;

            if (!is_file($source)) {
                continue;
            }

            /**
             * Only ZIP backup files.
             */
            if ('zip' !== strtolower(pathinfo($source, PATHINFO_EXTENSION))) {
                continue;
            }

            $destination = trailingslashit($backup_dir) . basename($file);

            if (copy($source, $destination)) {

                $imported[] = basename($file);
            }
        }

        /**
         * Cleanup uploaded ZIP.
         */
        if (file_exists($zip_path)) {
            wp_delete_file($zip_path);
        }

        wp_send_json_success(
            array(
                'message' => __('Backup files imported successfully.', 'norivive-backup-tools-for-vivid-backup'),
                'imported' => $imported,
            )
        );
    }
}
