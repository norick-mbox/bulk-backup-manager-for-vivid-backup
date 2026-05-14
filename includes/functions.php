<?php
/**
 * Helper functions.
 *
 * @package BulkBackupManagerForWPvivid
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get WPvivid backup directory.
 *
 * @return string
 */
function bbmwpv_get_backup_dir()
{

    return trailingslashit(WP_CONTENT_DIR) . 'wpvividbackups/';
}

/**
 * Get temporary directory.
 *
 * @return string
 */
function bbmwpv_get_temp_dir()
{

    $upload_dir = wp_upload_dir();

    return trailingslashit(
        $upload_dir['basedir']
    ) . 'bbmwpv-temp/';
}

/**
 * Get import directory.
 *
 * @return string
 */
function bbmwpv_get_import_dir()
{

    $upload_dir = wp_upload_dir();

    return trailingslashit(
        $upload_dir['basedir']
    ) . 'bbmwpv-import/';
}

/**
 * Check if current user can manage backups.
 *
 * @return bool
 */
function bbmwpv_current_user_can_manage()
{

    return current_user_can('manage_options');
}

/**
 * Get backup files list.
 *
 * @return array
 */
function bbmwpv_get_backup_files()
{

    $backup_dir = bbmwpv_get_backup_dir();

    if (!file_exists($backup_dir)) {
        return array();
    }

    $files = scandir($backup_dir);

    if (false === $files) {
        return array();
    }

    $results = array();

    foreach ($files as $file) {

        if ('.' === $file || '..' === $file) {
            continue;
        }

        $path = $backup_dir . $file;

        if (!is_file($path)) {
            continue;
        }

        $extension = strtolower(
            pathinfo($path, PATHINFO_EXTENSION)
        );

        if ('zip' !== $extension) {
            continue;
        }

        $results[] = array(
            'name' => basename($path),
            'path' => $path,
            'size' => size_format(filesize($path)),
            'modified' => gmdate(
                'Y-m-d H:i:s',
                filemtime($path)
            ),
        );
    }

    return $results;
}

/**
 * Sanitize filename.
 *
 * @param string $filename File name.
 * @return string
 */
function bbmwpv_sanitize_filename($filename)
{

    $filename = sanitize_file_name($filename);

    /**
     * Prevent path traversal.
     */
    $filename = str_replace(
        array('../', '..\\'),
        '',
        $filename
    );

    return $filename;
}
