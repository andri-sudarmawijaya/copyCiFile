<?php
namespace AndriSudarmawijaya\Copyfile10;

class Installer {

    public static function postInstall()
    {
        self::packageInstall();
    }

    public static function postUpdate()
    {
        self::packageInstall();
    }


    private static function packageInstall()
    {
        $source = './controllers';
        $dest = '../../../application/controllers';

        $directory = array(
            'controllers' => [
                'source' => './controllers',
                'dest' => '../../../application/controllers'
            ],
            'models' => [
                'source' => './models',
                'dest' => '../../../application/models'
            ],
            'views' => [
                'source' => './views',
                'dest' => '../../../application/views'
            ]
        );

        foreach($directory as $key => $target){
            $dir_copy = self::recursiveCopy($target['source'], $target['dest']);
            if ($dir_copy) {
                echo "Copying " . $target['source'] . "to the application directory.";
            } else {
                echo "Cannot copy " . $target['source'] . "to the application directory.";
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
                    self::recursiveCopy($src . '/' . $file,$dst . '/' . $file);
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