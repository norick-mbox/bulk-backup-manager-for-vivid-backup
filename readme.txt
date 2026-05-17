=== Bulk Backup Manager for Vivid Backup ===
Contributors: noricksaeki
Tags: backup, wpvivid, migration, restore, download
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 0.1.0
Required plugin:WPvivid Backup Plugin
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Bulk download and upload manager for Vivid Backup environments.

== Description ==

Bulk Backup Manager for Vivid Backup is an independent backup bundle management plugin.

This plugin allows administrators to:

- Bulk download backup ZIP files
- Upload backup bundles
- Restore multiple backup archives into WPvivid backup storage
- Manage backup bundles more efficiently

Features:

- Bulk ZIP bundle download
- ZIP bundle upload/import
- No recompression mode for faster processing
- ZIP bomb protection
- WordPress coding standards friendly
- Lightweight admin integration

This plugin is an independent third-party add-on and is not affiliated with any backup plugin vendor.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/bulk-backup-manager-for-vivid-backup` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Make sure WPvivid Backup Plugin is installed and activated.
4. Open the WPvivid backup page.

== Frequently Asked Questions ==

= Does this plugin require the Vivid Backup plugin? =

Yes.
WPvivid Backup Plugin must be installed and activated.

= Does this plugin modify WPvivid core files? =

No.
This plugin works as an independent add-on.

= Are backup ZIP files recompressed? =

No.
The plugin stores ZIP files without additional recompression whenever possible.

== Screenshots ==

1. Bulk download toolbar
2. Backup bundle upload form
3. Imported backup list

== Notes for Plugin Review Team ==

This plugin uses readfile() in the download endpoint intentionally for ZIP stream downloads.

The plugin handles potentially large backup bundle files generated from WPvivid backups. Using WP_Filesystem::get_contents() would load the entire ZIP into memory and may cause memory exhaustion on large backup archives.

The implementation:

* validates user capability
* validates download token
* validates file existence
* sends appropriate download headers
* deletes the temporary ZIP after download

The file is generated temporarily and removed immediately after streaming.


== Changelog ==

= 0.1.0 =
* Initial release.

== Upgrade Notice ==

= 0.1.0 =
Initial release.