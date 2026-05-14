<?php
/**
 * Security class.
 *
 * @package BulkBackupManagerForWPvivid
 */

if (!defined('ABSPATH')) {
    exit;
}

class BBMWPV_Security
{

    /**
     * Initialize hooks.
     *
     * @return void
     */
    public function init()
    {

        add_action(
            'admin_init',
            array($this, 'protect_temp_directory')
        );
    }

    /**
     * Protect temporary upload directory.
     *
     * @return void
     */
    public function protect_temp_directory()
    {

        $upload_dir = wp_upload_dir();

        $temp_dirs = array(
            trailingslashit($upload_dir['basedir']) . 'bbmwpv-temp',
            trailingslashit($upload_dir['basedir']) . 'bbmwpv-import',
        );

        foreach ($temp_dirs as $dir) {

            if (!file_exists($dir)) {
                wp_mkdir_p($dir);
            }

            $this->create_index_file($dir);

            $this->create_htaccess($dir);
        }
    }

    /**
     * Create blank index.php.
     *
     * @param string $dir Directory path.
     * @return void
     */
    private function create_index_file($dir)
    {

        $file = trailingslashit($dir) . 'index.php';

        if (file_exists($file)) {
            return;
        }

        $content = "<?php\n// Silence is golden.";

        wp_filesystem();

        global $wp_filesystem;

        if ($wp_filesystem) {
            $wp_filesystem->put_contents(
                $file,
                $content,
                FS_CHMOD_FILE
            );
        }
    }

    /**
     * Create .htaccess file.
     *
     * @param string $dir Directory path.
     * @return void
     */
    private function create_htaccess($dir)
    {

        $file = trailingslashit($dir) . '.htaccess';

        if (file_exists($file)) {
            return;
        }

        $content = <<<HTACCESS
Options -Indexes

<FilesMatch "\.(zip|gz|tar)$">
    Require all denied
</FilesMatch>
HTACCESS;

        wp_filesystem();

        global $wp_filesystem;

        if ($wp_filesystem) {

            $wp_filesystem->put_contents(
                $file,
                $content,
                FS_CHMOD_FILE
            );
        }
    }
}