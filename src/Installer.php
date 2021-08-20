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

        $filename = './config/local-example.php';

        if (file_exists($filename)) {
            echo "Copying $filename to the root directory.";
            copy($filename, './local-2.php');

        } else {
            echo "$filename does not exist, cannot copy it to the root directory";
        }
    }
}