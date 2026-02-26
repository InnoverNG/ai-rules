<?php

declare(strict_types=1);

namespace Innoverng\AiRules;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;

class AiRulesPlugin implements PluginInterface, EventSubscriberInterface
{
    private Composer $composer;
    private IOInterface $io;
    private Installer $installer;

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->installer = new Installer($composer, $io);
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        // Nothing to deactivate
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // We intentionally do not delete user files on uninstall
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => [['onPackageInstall', 0]],
            PackageEvents::POST_PACKAGE_UPDATE   => [['onPackageUpdate', 0]],
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
}
