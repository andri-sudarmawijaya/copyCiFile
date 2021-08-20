<?php
namespace AndriSudarmawijaya\Copyfile10;

class Installer {

    public static function postInstall()
    {
        $filename = './config/local-example.php';

        if (file_exists($filename)) {
            echo "Copying $filename to the root directory.";
            copy($filename, './local-1.php');

        } else {
            echo "$filename does not exist, cannot copy it to the root directory";
        }
    }

    public static function postUpdate()
    {
/*
        $filename = './config/local-example.php';

        if (file_exists($filename)) {
            echo "Copying $filename to the root directory.";
            copy($filename, './local-2.php');
            copy($filename, '../../local-7.php');
            copy($filename, '../../../local-8.php');

        } else {
            echo "$filename does not exist, cannot copy it to the root directory";
        }
*/

        $controller = './controllers';
        if (file_exists($controller)) {
            echo "Copying $controller to the application directory.";
            copy($controller, '../../../application/');

        } else {
            echo "$controller does not exist, cannot copy it to the application directory";
        }
    }


    /**
     * Recursive Copy
     *
     * @param string $src
     * @param string $dst
     */
    private static function recursiveCopy($src, $dst)
    {
        mkdir($dst, 0755);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                mkdir($dst . '/' . $iterator->getSubPathName());
            } else {
                copy($file, $dst . '/' . $iterator->getSubPathName());
            }
        }
    }
}