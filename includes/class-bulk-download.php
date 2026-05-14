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

        $files = isset($_POST['files'])
        ? (array) $_POST['files']
        : array();

        if (empty($files)) {

            wp_send_json_error(
                array(
                    'message' => 'No backups selected.',
                )
            );
        }

        wp_send_json_success(
            array(
                'message' => 'Bulk download test success.',
                'url' => admin_url(),
            )
        );
    }
}
