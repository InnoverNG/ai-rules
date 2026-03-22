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
    private string $backupDir;

    /** Relative destination paths (from project root) — must stay in sync with stubs/ layout */
    private const FILES = [
        '.cursor/rules/blade-no-php.mdc',
        '.cursor/rules/bootstrap.mdc',
        '.cursor/rules/cursorrules.mdc',
        '.cursor/rules/laravel-best-practice.mdc',
        '.cursor/rules/laravel-boost.mdc',
        '.cursor/rules/structure.mdc',
        '.claude/CLAUDE.md',
        '.github/copilot-instructions.md',
        'docs/README.md',
    ];

    /**
     * Directories whose files should NOT be gitignored (user owns these).
     * docs/ is intentional — users commit their own documentation.
     */
    private const GITIGNORE_SKIP_DIRS = ['docs'];

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

        $this->backupDir = $this->projectRoot . '/.ai-rules-backup';
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

        $this->writeGitignores();
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

        $installer->writeGitignores();
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

        // Backup: remove old backup, save current local copy, then overwrite
        $backupPath = $this->backupDir . '/' . $relativePath;
        $this->ensureDirectory($backupPath);

        if (file_exists($backupPath)) {
            unlink($backupPath);
        }

        copy($dest, $backupPath);
        copy($source, $dest);

        $this->io->write("  <info>[ai-rules] Updated:</info> {$relativePath} <comment>(previous version backed up to .ai-rules-backup/{$relativePath})</comment>");
    }

    /**
     * Writes a managed # BEGIN ai-rules / # END ai-rules block into the .gitignore
     * of each directory that contains files we manage. Derives entries directly from
     * FILES so it stays in sync automatically when new stubs are added.
     * docs/ is skipped — users own and commit their own documentation.
     */
    private function writeGitignores(): void
    {
        // Group managed files by their directory, skipping excluded dirs
        $dirs = [];
        foreach (self::FILES as $relativePath) {
            $dir = dirname($relativePath);
            if (in_array($dir, self::GITIGNORE_SKIP_DIRS, strict: true) || $dir === '.') {
                continue;
            }
            $dirs[$dir][] = basename($relativePath);
        }

        foreach ($dirs as $dir => $entries) {
            $this->writeManagedBlock(
                $this->projectRoot . '/' . $dir . '/.gitignore',
                $entries
            );
        }

        // Backup folder: ignore everything, but track the .gitignore itself
        $backupGitignore = $this->backupDir . '/.gitignore';
        $this->ensureDirectory($backupGitignore);
        if (!file_exists($backupGitignore)) {
            file_put_contents($backupGitignore, "*\n!.gitignore\n");
        }
    }

    /**
     * Inserts or replaces the managed block in a .gitignore file.
     * Any content outside the markers is left untouched.
     */
    private function writeManagedBlock(string $gitignorePath, array $entries): void
    {
        $block  = "# BEGIN ai-rules (managed — do not edit this block)\n";
        $block .= implode("\n", $entries) . "\n";
        $block .= "# END ai-rules\n";

        $this->ensureDirectory($gitignorePath);

        if (!file_exists($gitignorePath)) {
            file_put_contents($gitignorePath, $block);
            return;
        }

        $existing = file_get_contents($gitignorePath);
        $pattern  = '/# BEGIN ai-rules.*?# END ai-rules\n?/s';

        $updated = preg_match($pattern, $existing)
            ? preg_replace($pattern, $block, $existing)
            : rtrim($existing) . "\n\n" . $block;

        if ($updated !== $existing) {
            file_put_contents($gitignorePath, $updated);
        }
    }

    private function ensureDirectory(string $filePath): void
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, recursive: true);
        }
    }
}
