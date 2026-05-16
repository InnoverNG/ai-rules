<?php

declare(strict_types=1);

namespace Innoverng\AiRules;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class AiRulesPlugin implements PluginInterface, EventSubscriberInterface
{
    private Installer $installer;

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->installer = new Installer($composer, $io);
    }

    public function deactivate(Composer $_composer, IOInterface $_io): void
    {
        // Nothing to deactivate
    }

    public function uninstall(Composer $_composer, IOInterface $_io): void
    {
        // We intentionally do not delete user files on uninstall
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Package events fire for our specific package regardless of --no-scripts
            PackageEvents::POST_PACKAGE_INSTALL => [['onPackageInstall', 0]],
            PackageEvents::POST_PACKAGE_UPDATE   => [['onPackageUpdate', 0]],
            // Script event as a safety net: recreates missing stubs on composer install
            // (e.g. fresh clone where vendor is populated but stub files were deleted)
            ScriptEvents::POST_INSTALL_CMD => [['onPostInstall', 0]],
        ];
    }

    public function onPackageInstall(PackageEvent $event): void
    {
        $operation = $event->getOperation();

        if (!$operation instanceof InstallOperation) {
            return;
        }

        if ($operation->getPackage()->getName() !== 'innoverng/ai-rules') {
            return;
        }

        $this->installer->copyStubs(install: true);
    }

    public function onPackageUpdate(PackageEvent $event): void
    {
        $operation = $event->getOperation();

        if (!$operation instanceof UpdateOperation) {
            return;
        }

        if ($operation->getTargetPackage()->getName() !== 'innoverng/ai-rules') {
            return;
        }

        $this->installer->copyStubs(install: false);
    }

    public function onPostInstall(Event $event): void
    {
        $this->installer->copyStubs(install: true);
    }
}
