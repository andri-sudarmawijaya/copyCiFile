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
        $source = './controllers';
        $dest = '../../../application/controllers';

        $directory = [
            $controllers = [
                $source = './controllers',
                $dest = '../../../application/controllers'
            ],
            $models = [
                $source = './controllers',
                $dest = '../../../application/controllers'
            ],
            $views = [
                $source = './controllers',
                $dest = '../../../application/controllers'
            ]
        ];

        foreach($directory as $target){
            $dir_copy = self::recursiveCopy($target['source'], $target['dest']);
            if ($dir_copy) {
                echo "Copying $target['source'] to the application directory.";
            } else {
                echo "Cannot copy $target['source'] to the application directory.";
            }
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
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    recursiveCopy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
        return TRUE;
    }
}