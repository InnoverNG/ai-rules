<?php

declare(strict_types=1);

namespace Innoverng\AiRules;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Script\Event;

class Installer
{
    private string $projectRoot;
    private string $stubsDir;

    /** Relative destination paths (from project root) — must stay in sync with stubs/ layout */
    private const FILES = [
        '.cursorrules',
        'CLAUDE.md',
        '.github/copilot-instructions.md',
    ];

    public function __construct(
        private readonly Composer $composer,
        private readonly IOInterface $io
    ) {
        // Factory::getComposerFile() correctly respects the COMPOSER env var override,
        // giving us the consuming project's root regardless of the current working directory.
        $composerFile = Factory::getComposerFile();
        $this->projectRoot = realpath(dirname($composerFile)) ?: dirname($composerFile);

        // Resolve stub path via InstallationManager so symlinks / custom vendor dirs work.
        $localRepo = $this->composer->getRepositoryManager()->getLocalRepository();
        $package   = $localRepo->findPackage('innoverng/ai-rules', '*');

        $this->stubsDir = $package
            ? $this->composer->getInstallationManager()->getInstallPath($package) . '/stubs'
            : __DIR__ . '/../stubs'; // fallback during self-install
    }

    public function copyStubs(bool $install): void
    {
        foreach (self::FILES as $relativePath) {
            $source = $this->stubsDir . '/' . $relativePath;
            $dest   = $this->projectRoot . '/' . $relativePath;

            if (!file_exists($source)) {
                $this->io->writeError("  <warning>[ai-rules] Stub missing: {$relativePath}</warning>");
                continue;
            }

            if ($install) {
                $this->handleInstall($source, $dest, $relativePath);
            } else {
                $this->handleUpdate($source, $dest, $relativePath);
            }
        }
    }

    /**
     * Called as a Composer script alias to force-copy all stubs, overwriting local copies.
     *
     * Add to your project's composer.json:
     *   "scripts": { "ai-rules:update": "Innoverng\\AiRules\\Installer::forceUpdate" }
     *
     * Then run: composer ai-rules:update
     */
    public static function forceUpdate(Event $event): void
    {
        $installer = new self($event->getComposer(), $event->getIO());

        foreach (self::FILES as $relativePath) {
            $source = $installer->stubsDir . '/' . $relativePath;
            $dest   = $installer->projectRoot . '/' . $relativePath;

            if (!file_exists($source)) {
                $event->getIO()->writeError("  <warning>[ai-rules] Stub missing: {$relativePath}</warning>");
                continue;
            }

            $installer->ensureDirectory($dest);
            copy($source, $dest);
            $event->getIO()->write("  <info>[ai-rules] Force-copied:</info> {$relativePath}");
        }
    }

    private function handleInstall(string $source, string $dest, string $relativePath): void
    {
        if (file_exists($dest)) {
            $this->io->write("  <info>[ai-rules] Skipped (already exists):</info> {$relativePath}");
            return;
        }

        $this->ensureDirectory($dest);
        copy($source, $dest);
        $this->io->write("  <info>[ai-rules] Created:</info> {$relativePath}");
    }

    private function handleUpdate(string $source, string $dest, string $relativePath): void
    {
        if (!file_exists($dest)) {
            // File was never created locally — treat as a fresh install
            $this->handleInstall($source, $dest, $relativePath);
            return;
        }

        if (md5_file($source) === md5_file($dest)) {
            $this->io->write("  <info>[ai-rules] Up to date:</info> {$relativePath}");
            return;
        }

        $this->io->write("  <comment>[ai-rules] Rule file updated in package but NOT overwritten:</comment> {$relativePath}");
        $this->io->write("    Review new stub: vendor/innoverng/ai-rules/stubs/{$relativePath}");
        $this->io->write("    Merge manually, or run:  composer ai-rules:update");
    }

    private function ensureDirectory(string $filePath): void
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, recursive: true);
        }
    }
}
