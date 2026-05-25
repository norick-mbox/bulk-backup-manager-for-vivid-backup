<?php
/**
 * ZIP handler class.
 *
 * @package NoriviveBackupToolsForVividBackup
 */

if (!defined('ABSPATH')) {
    exit;
}

class BBMWPV_Zip_Handler
{

    /**
     * Create ZIP archive.
     *
     * @param array  $files    Files to add.
     * @param string $zip_path ZIP file path.
     * @return bool
     */
    public function create_zip($files, $zip_path)
    {

        if (empty($files) || empty($zip_path)) {
            return false;
        }

        $zip = new ZipArchive();

        $result = $zip->open(
            $zip_path,
            ZipArchive::CREATE | ZipArchive::OVERWRITE
        );

        if (true !== $result) {
            return false;
        }

        foreach ($files as $file) {

            if (!file_exists($file)) {
                continue;
            }

            $local_name = basename($file);

            $zip->addFile($file, $local_name);

            /**
             * Store without recompression.
             */
            if (method_exists($zip, 'setCompressionName')) {

                $zip->setCompressionName(
                    $local_name,
                    ZipArchive::CM_STORE
                );
            }
        }

        $zip->close();

        return file_exists($zip_path);
    }

    /**
     * Extract ZIP archive.
     *
     * @param string $zip_path    ZIP file path.
     * @param string $extract_dir Extract directory.
     * @return bool
     */
    public function extract_zip($zip_path, $extract_dir)
    {

        if (!file_exists($zip_path)) {
            return false;
        }

        if (!file_exists($extract_dir)) {
            wp_mkdir_p($extract_dir);
        }

        $zip = new ZipArchive();

        $result = $zip->open($zip_path);

        if (true !== $result) {
            return false;
        }

        /**
         * ZIP bomb protection.
         */
        if ($zip->numFiles > 1000) {

            $zip->close();

            return false;
        }

        $zip->extractTo($extract_dir);

        $zip->close();

        return true;
    }

    /**
     * Delete temporary directory recursively.
     *
     * @param string $dir Directory path.
     * @return void
     */
    public function delete_directory($dir)
    {

        if (!file_exists($dir)) {
            return;
        }

        $items = scandir($dir);

        if (false === $items) {
            return;
        }

        foreach ($items as $item) {

            if ('.' === $item || '..' === $item) {
                continue;
            }

            $path = trailingslashit($dir) . $item;

            if (is_dir($path)) {

                $this->delete_directory($path);

            } else {

                wp_delete_file($path);
            }
        }

        global $wp_filesystem;

        WP_Filesystem();

        $wp_filesystem->rmdir(
            $dir,
            true
        );

    }
}
