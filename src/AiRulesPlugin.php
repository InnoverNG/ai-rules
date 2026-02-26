<?php

declare(strict_types=1);

namespace Innoverng\AiRules;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
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
            ScriptEvents::POST_INSTALL_CMD => [['onPostInstall', 0]],
            ScriptEvents::POST_UPDATE_CMD  => [['onPostUpdate', 0]],
        ];
    }

    public function onPostInstall(Event $event): void
    {
        $this->installer->copyStubs(install: true);
    }

    public function onPostUpdate(Event $event): void
    {
        $this->installer->copyStubs(install: false);
    }
}
