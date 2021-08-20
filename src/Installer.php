<?php
namespace AndriSudarmawijaya\Copyfile10;

class Installer {

    use Composer\Script\Event;
    use Composer\Installer\PackageEvent;

    public function __construct()
    {
        echo "hello, i am a page.";
    }

    public static function postPackageInstall(PackageEvent $event = NULL)
    {
        $installedPackage = $event->getOperation()->getPackage();
        // do stuff
    }


    public static function postInstall(Event $event = NULL)
    {
        $composer = $event->getComposer();
        // do stuff
        $filename = './app/code/Vendor/Module/file.php';

        if (file_exists($filename)) {
            echo "Copying $filename to the root directory.";
            copy($filename, './file.php');

        } else {
            echo "$filename does not exist, cannot copy it to the root directory";
        }

        $io = $event->getIO();
        $io->write("hello, i am install a page.");
    }

    public static function postUpdate()
    {
        //$composer = $event->getComposer();
        // do stuff

        copy('vendor/codeigniter/framework/.gitignore', 'gitignore');
        copy('vendor/codeigniter/framework/.gitignore', './gitignore1');

        //$io = $event->getIO();
        //$io->write("hello, i am update a page.");

        echo "hello, i am update a page.";
    }

    public static function postAutoloadDump(Event $event = NULL)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        //some_function_from_an_autoloaded_file();
    }

    public static function warmCache(Event $event = NULL)
    {
        // make cache toasty
    }

    private static function composerUpdate()
    {
        passthru('composer update');
    }
}