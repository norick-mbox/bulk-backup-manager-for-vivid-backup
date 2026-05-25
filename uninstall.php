<?php
/**
 * Uninstall file for Bulk Backup Manager for WPvivid.
 *
 * @package NoriviveBackupToolsForVividBackup
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Delete plugin options.
 */

delete_option('bbmwpv_settings');
delete_option('bbmwpv_version');

/**
 * Delete multisite options.
 */

if (is_multisite()) {

    $bbmwpv_sites = get_sites(
        array(
            'fields' => 'ids',
        )
    );

   foreach ($bbmwpv_sites as $bbmwpv_site_id) {

        switch_to_blog($bbmwpv_site_id);


        delete_option('bbmwpv_settings');
        delete_option('bbmwpv_version');

        restore_current_blog();
    }
}
