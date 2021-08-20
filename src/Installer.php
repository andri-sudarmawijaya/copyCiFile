<?php
namespace AndriSudarmawijaya\Copyfile10;

class Installer {

    use Composer\Script\Event;
    use Composer\Installer\PackageEvent;

    public function __construct()
    {
        echo "hello, i am a page.";
    }

    public static function postUpdate(Event $event = NULL)
    {
        $composer = $event->getComposer();
        // do stuff
    }

    public static function postAutoloadDump(Event $event = NULL)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        some_function_from_an_autoloaded_file();
    }

    public static function postPackageInstall(PackageEvent $event = NULL)
    {
        $installedPackage = $event->getOperation()->getPackage();
        // do stuff
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