<?php
    /**
 * Plugin Name: Bulk Backup Manager for WPvivid
 * Plugin URI: https://example.com/
 * Description: Bulk download and upload manager for WPvivid Backup Plugin.
 * Version: 0.1.0
 * Author: Norick Saeki
 * Author URI: https://norick-mbox.com/
 * Text Domain: bulk-backup-manager-for-wpvivid
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Tested up to: 6.8
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package BulkBackupManagerForWPvivid
 */

    if (!defined('ABSPATH')) {
    exit;
    }

    /**
 * Plugin version.
 */
    define('BBMWPV_VERSION', '0.1.0');

    /**
 * Plugin path.
 */
    define('BBMWPV_PLUGIN_PATH', plugin_dir_path(__FILE__));

    /**
 * Plugin URL.
 */
    define('BBMWPV_PLUGIN_URL', plugin_dir_url(__FILE__));

    /**
 * Plugin basename.
 */
    define('BBMWPV_PLUGIN_BASENAME', plugin_basename(__FILE__));

    /**
 * Check if WPvivid is active.
 *
 * @return bool
 */
    function bbmwpv_is_wpvivid_active()
    {

    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    return is_plugin_active('wpvivid-backuprestore/wpvivid-backuprestore.php');
    }

    /**
 * Admin notice when WPvivid is not active.
 *
 * @return void
 */
    function bbmwpv_wpvivid_missing_notice()
    {

    if (bbmwpv_is_wpvivid_active()) {
        return;
    }

    ?>
	<div class="notice notice-error">
		<p>
			<?php
                echo esc_html__(
                        'Bulk Backup Manager for WPvivid requires WPvivid Backup Plugin to be installed and activated.',
                        'bulk-backup-manager-for-wpvivid'
                    );
                ?>
		</p>
	</div>
	<?php
        }
        add_action('admin_notices', 'bbmwpv_wpvivid_missing_notice');

        /**
         * Load plugin textdomain.
         *
         * @return void
         */
        function bbmwpv_load_textdomain()
        {

            load_plugin_textdomain(
        'bulk-backup-manager-for-wpvivid',
        false,
        dirname(BBMWPV_PLUGIN_BASENAME) . '/languages'
            );
        }
        add_action('plugins_loaded', 'bbmwpv_load_textdomain');

        /**
         * Load plugin files.
         *
         * @return void
         */
        function bbmwpv_load_plugin()
        {

            if (!bbmwpv_is_wpvivid_active()) {
        return;
            }

            require_once BBMWPV_PLUGIN_PATH . 'includes/class-plugin.php';

            if (class_exists('BBMWPV_Plugin')) {

        $plugin = new BBMWPV_Plugin();
        $plugin->init();
            }
    }
    add_action('plugins_loaded', 'bbmwpv_load_plugin', 20);