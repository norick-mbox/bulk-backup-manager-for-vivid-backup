<?php

if (!defined('ABSPATH')) {
    exit;
}

class BBMWPV_WPvivid_Integration
{

    public function init()
    {
        // Reserved for future WPvivid integrations.
    }

    private function is_wpvivid_screen()
    {

        if (!function_exists('get_current_screen')) {
            return false;
        }

        $screen = get_current_screen();

        if (empty($screen->id)) {
            return false;
        }

        return false !== stripos(
            $screen->id,
            'wpvivid'
        );
    }
}
