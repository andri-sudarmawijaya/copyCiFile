<?php
namespace AndriSudarmawijaya\CopyCifile;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Composer\Script\Event;

class ScriptHandler
{
    /**
     * @param Event $event
     */
    public static function copy(Event $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();
        $extraField = $event->isDevMode() && isset($extras['copy-file-dev']) ? 'copy-file-dev' : 'copy-file';

        $io = $event->getIO();
        if (!isset($extras[$extraField])) {
            $io->write("No dirs or files are configured through the extra.{$extraField} setting.");

            return;
        }

        $files = $extras[$extraField];

        if (!is_array($files) || $files === array_values($files)) {
            $io->write("The extra.{$extraField} must be hash like \"\{<dir_or_file_from>: <dir_to>\}\".");

            return;
        }

        $fs = new Filesystem;

        foreach ($files as $from => $to) {
            // check pattern
            $pattern = null;
            if (strpos($from, '#') > 0) {
                list($from, $pattern) = explode('#', $from, 2);
            }

            // check the overwrite newer files disable flag (? in end of path)
            $overwriteNewerFiles = substr($to, -1) != '?';
            if (!$overwriteNewerFiles) {
                $to = substr($to, 0, -1);
            }

            // Check the renaming of file for direct moving (file-to-file)
            $isRenameFile = substr($to, -1) != '/' && !is_dir($from);

            if (file_exists($to) && !is_dir($to) && !$isRenameFile) {
                throw new \InvalidArgumentException('Destination directory is not a directory.');
            }

            try {
                if ($isRenameFile) {
                    $fs->mkdir(dirname($to));
                } else {
                    $fs->mkdir($to);
                }
            } catch (IOException $e) {
                throw new \InvalidArgumentException(sprintf('<error>Could not create directory %s.</error>', $to), $e->getCode(), $e);
            }

            if (false === file_exists($from)) {
                throw new \InvalidArgumentException(sprintf('<error>Source directory or file "%s" does not exist.</error>', $from));
            }

            if (is_dir($from)) {
                $finder = new Finder;
                $finder->files()->ignoreDotFiles(false)->in($from);

                if ($pattern) {
                    $finder->path("#{$pattern}#");
                }

                foreach ($finder as $file) {
                    $dest = sprintf('%s/%s', $to, $file->getRelativePathname());

                    try {
                        $fs->copy($file, $dest, $overwriteNewerFiles);
                    } catch (IOException $e) {
                        throw new \InvalidArgumentException(sprintf('<error>Could not copy %s</error>', $file->getBaseName()), $e->getCode(), $e);
                    }
                }
            } else {
                try {
                    if ($isRenameFile) {
                        $fs->copy($from, $to, $overwriteNewerFiles);
                    } else {
                        $fs->copy($from, $to.'/'.basename($from), $overwriteNewerFiles);
                    }
                } catch (IOException $e) {
                    throw new \InvalidArgumentException(sprintf('<error>Could not copy %s</error>', $from), $e->getCode(), $e);
                }
            }

            $io->write(sprintf('Copied file(s) from <comment>%s</comment> to <comment>%s</comment>.', $from, $to));
        }
    }
}

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