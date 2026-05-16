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

        $rawStubsDir    = $package
            ? $this->composer->getInstallationManager()->getInstallPath($package) . '/stubs'
            : __DIR__ . '/../stubs'; // fallback during self-install
        $this->stubsDir = realpath($rawStubsDir) ?: $rawStubsDir;

        $this->backupDir = $this->projectRoot . '/.ai-rules-backup';
    }

    public function copyStubs(bool $install): void
    {
        foreach ($this->discoverStubFiles() as $relativePath) {
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

        foreach ($installer->discoverStubFiles() as $relativePath) {
            $source = $installer->stubsDir . '/' . $relativePath;
            $dest   = $installer->projectRoot . '/' . $relativePath;

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
     * Writes a managed block in the project root .gitignore (creates the file if missing).
     * Ignores .ai-rules-backup/ and every package-managed stub path. docs/ is excluded.
     */
    private function writeGitignores(): void
    {
        $entries = ['.ai-rules-backup/'];

        foreach ($this->discoverStubFiles() as $relativePath) {
            if ($this->shouldSkipGitignoreForStub($relativePath)) {
                continue;
            }
            $entries[] = $relativePath;
        }

        sort($entries);
        // Keep backup folder first after sort (.ai-rules-backup/ sorts before paths)
        $entries = array_values(array_unique($entries));

        $this->writeManagedBlock($this->projectRoot . '/.gitignore', $entries);
    }

    private function shouldSkipGitignoreForStub(string $relativePath): bool
    {
        if ($relativePath === '.' || dirname($relativePath) === '.') {
            return true;
        }

        $dir = dirname($relativePath);

        if (in_array($dir, self::GITIGNORE_SKIP_DIRS, strict: true)) {
            return true;
        }

        foreach (self::GITIGNORE_SKIP_DIRS as $skipDir) {
            if (str_starts_with($relativePath, $skipDir . '/')) {
                return true;
            }
        }

        return false;
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

    /**
     * All files under stubs/, relative to the stubs root.
     * New stubs are picked up automatically — no manual list in this class.
     *
     * @return list<string>
     */
    private function discoverStubFiles(): array
    {
        if (!is_dir($this->stubsDir)) {
            return [];
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->stubsDir, \FilesystemIterator::SKIP_DOTS)
        );

        $stubsRoot = rtrim(str_replace('\\', '/', $this->stubsDir), '/');

        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $path = str_replace('\\', '/', $file->getPathname());
            $relative = ltrim(substr($path, strlen($stubsRoot)), '/');
            $files[] = $relative;
        }

        sort($files);

        return $files;
    }
}
