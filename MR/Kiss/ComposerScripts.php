<?php
namespace MR\Kiss;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class ComposerScripts
{
    /**
     * Because MunkiReport had its own custom PHP autoloader which looked for the '_model' suffix in the class name,
     * we can't replicate this behaviour in a normal composer autoload configuration.
     *
     * So, as a dirty hack, we copy a modified version of composer's ClassLoader.php which includes a check for the
     * '_model' suffix in loadClass() whenever composer dump-autoload is run.
     */
    public static function postAutoloadDump(Event $event)
    {
        $io = $event->getIO();
        $io->write("Copying modified ClassLoader.php for MunkiReport Legacy Modules...");
        echo $event->getName();

        $app_root = realpath(dirname(__FILE__) . '/../../');
        $io->write($app_root);

        copy($app_root . '/shims/ClassLoader_Modified.php', $app_root . '/vendor/composer/ClassLoader.php');
    }
}
