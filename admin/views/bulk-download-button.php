<?php
    /**
 * Bulk download button view.
 *
 * @package BulkBackupManagerForWPvivid
 */

    if (!defined('ABSPATH')) {
    exit;
    }
?>

<div id="bbmwpv-toolbar" style="margin-top:10px;">

	<button
		type="button"
		class="button button-primary"
		id="bbmwpv-bulk-download"
	>
		<?php
            echo esc_html__(
                'Bulk Download',
                'bulk-backup-manager-for-vivid-backup'
            );
        ?>
	</button>

	<button
		type="button"
		class="button"
		id="bbmwpv-bulk-upload-open"
		disabled
	>
		<?php
            echo esc_html__(
                'Bulk Upload',
                'bulk-backup-manager-for-vivid-backup'
            );
        ?>
	</button>

</div>

<div id="bbmwpv-upload-area" style="display:none;margin-top:15px;">

	<form
		id="bbmwpv-upload-form"
		enctype="multipart/form-data"
	>

		<input
			type="file"
			name="file"
			id="bbmwpv-upload-file"
			accept=".zip"
		>

		<button
			type="submit"
			class="button button-secondary"
		>
			<?php
                echo esc_html__(
                    'Upload Backup Bundle',
                    'bulk-backup-manager-for-vivid-backup'
                );
            ?>
		</button>

	</form>

	<div id="bbmwpv-upload-result"></div>

</div>