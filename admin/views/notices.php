<?php
    /**
 * Admin notices view.
 *
 * @package BulkBackupManagerForWPvivid
 */

    if (!defined('ABSPATH')) {
    exit;
    }

    /**
 * Expected variables:
 *
 * @var string $type    success|error|warning|info
 * @var string $message Notice message.
 */

    $type = isset($type) ? sanitize_html_class($type) : 'info';
    $message = isset($message) ? wp_kses_post($message) : '';

    if (empty($message)) {
    return;
    }

    $allowed_types = array(
    'success',
    'error',
    'warning',
    'info',
    );

    if (!in_array($type, $allowed_types, true)) {
    $type = 'info';
    }

    $notice_class = 'notice';

    switch ($type) {

    case 'success':
        $notice_class .= ' notice-success';
        break;

    case 'error':
        $notice_class .= ' notice-error';
        break;

    case 'warning':
        $notice_class .= ' notice-warning';
        break;

    default:
        $notice_class .= ' notice-info';
        break;
    }
?>

<div class="<?php echo esc_attr($notice_class); ?> is-dismissible">

	<p>
		<?php echo wp_kses_post($message); ?>
	</p>

</div>