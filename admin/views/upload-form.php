<?php
    /**
 * Upload form view.
 *
 * @package BulkBackupManagerForWPvivid
 */

    if (!defined('ABSPATH')) {
    exit;
    }
?>

<div class="bbmwpv-upload-wrapper">

	<h3>
		<?php
            echo esc_html__(
                'Import Backup Bundle',
                'bulk-backup-manager-for-vivid-backup'
            );
        ?>
	</h3>

	<p>
		<?php
            echo esc_html__(
                'Upload a ZIP file that contains multiple WPvivid backup ZIP files.',
                'bulk-backup-manager-for-vivid-backup'
            );
        ?>
	</p>

	<form
		id="bbmwpv-upload-form"
		method="post"
		enctype="multipart/form-data"
	>

		<input
			type="file"
			id="bbmwpv-upload-file"
			name="file"
			accept=".zip"
			required
		>

		<button
			type="submit"
			class="button button-primary"
		>
			<?php
                echo esc_html__(
                    'Start Import',
                    'bulk-backup-manager-for-vivid-backup'
                );
            ?>
		</button>

	</form>

	<div id="bbmwpv-upload-status"></div>

</div>