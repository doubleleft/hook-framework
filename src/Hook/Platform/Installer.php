<?php namespace Hook\Platform;

use Hook\Http\Router;

class Installer
{
    const ASSETS_DIR = 'assets';

    public static function assets() {
        echo "=> Searching for package assets..." . PHP_EOL;

        // built-in assets
        static::copyPublicAssets(array_filter(static::searchFiles(__DIR__ . '/..'), function($file) {
            return (preg_match('/\/'.self::ASSETS_DIR.'\//', $file));
        }));

        // vendor
        static::copyPublicAssets(array_filter(static::searchFiles(__DIR__ . '/../../../vendor'), function($file) {
            return (preg_match('/\/'.self::ASSETS_DIR.'\//', $file));
        }));
    }

    public static function copyPublicAssets($files) {
        $paths = require('config/paths.php');

        foreach($files as $source_path) {
            preg_match('/\/'.self::ASSETS_DIR.'\/(.*)/', dirname($source_path), $matches);
            $dest_dir = (count($matches) == 2) ? $matches[1] : null;
            if ($dest_dir) {
                $dest_dir = $paths['root'] . 'public' . DIRECTORY_SEPARATOR . self::ASSETS_DIR . DIRECTORY_SEPARATOR . $dest_dir . DIRECTORY_SEPARATOR;

                // create directory
                if(!file_exists($dest_dir)) {
                    mkdir($dest_dir, 0777, true);
                }

                copy($source_path, $dest_dir . basename($source_path));
            }
        }
    }

    protected static function searchFiles($input_dir, &$response = array()) {
        $files = array_diff(scandir($input_dir), array('.','..'));

        foreach ($files as $file) {
            if (is_dir("$input_dir/$file")) {
                static::searchFiles("$input_dir/$file", $response);
            } else {
                $response[] = "$input_dir/$file";
            }
        }

        return $response;
    }

}
