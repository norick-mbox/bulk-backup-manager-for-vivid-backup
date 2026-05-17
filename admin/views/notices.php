<?php
    /**
 * Admin notices view.
 *
 * @package BulkBackupManagerForVividBackup
 */

    if (!defined('ABSPATH')) {
    exit;
    }

    /**
 * Expected variables:
 *
 * @var string $bbmwpv_type    success|error|warning|info
 * @var string $bbmwpv_message Notice message.
 */

    $bbmwpv_type = isset($bbmwpv_type)
    ? sanitize_html_class($bbmwpv_type)
    : 'info';

    $bbmwpv_message = isset($bbmwpv_message)
    ? wp_kses_post($bbmwpv_message)
    : '';

    if (empty($bbmwpv_message)) {
    return;
    }

    $bbmwpv_allowed_types = array(
    'success',
    'error',
    'warning',
    'info',
    );

    if (
    !in_array(
        $bbmwpv_type,
        $bbmwpv_allowed_types,
        true
    )
    ) {
    $bbmwpv_type = 'info';
    }

    $bbmwpv_notice_class = 'notice';

    switch ($bbmwpv_type) {

    case 'success':
        $bbmwpv_notice_class .= ' notice-success';
        break;

    case 'error':
        $bbmwpv_notice_class .= ' notice-error';
        break;

    case 'warning':
        $bbmwpv_notice_class .= ' notice-warning';
        break;

    default:
        $bbmwpv_notice_class .= ' notice-info';
        break;
    }
?>

<div class="<?php echo esc_attr($bbmwpv_notice_class); ?> is-dismissible">

	<p>
		<?php echo wp_kses_post($bbmwpv_message); ?>
	</p>

</div>