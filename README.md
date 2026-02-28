# innoverng/ai-rules

Shareable AI rule files for [Cursor](https://cursor.sh), [Claude Code](https://claude.ai), and [GitHub Copilot](https://github.com/features/copilot).

Files are copied automatically to your project root when you run `composer require`.

## Files Installed

| File | Tool |
|------|------|
| `.cursor/rules/*.mdc` (cursorrules, laravel-boost, themes) | Cursor |
| `.claude/CLAUDE.md` | Claude Code |
| `.github/copilot-instructions.md` | GitHub Copilot |
| `docs/README.md` | Project documentation (starter index) |

Cursor rules live in `.cursor/rules/` (Cursor’s recommended path). Claude reads from `.claude/CLAUDE.md` (or project root); Copilot only reads `.github/copilot-instructions.md`.

## Installation

From your project root, run:

```bash
composer config repository.ai-rules vcs https://github.com/innoverng/ai-rules
composer config allow-plugins.innoverng/ai-rules true
composer require innoverng/ai-rules
```

No manual editing of `composer.json` — Composer adds the repository, plugin permission, and dependency for you.

**One-line install** (runs the same commands in the current directory):

```bash
curl -sSL https://raw.githubusercontent.com/innoverng/ai-rules/HEAD/install.sh | bash
```

**Alternative:** add the repository, require, and `allow-plugins` to your `composer.json` by hand, then run `composer update`. See the [manual snippet](#manual-composerjson-snippet) at the bottom of this README.

On first install the plugin copies all rule files to your project root. Files that already exist are **never overwritten** — your customisations are always safe.

## Updating Rule Files

When a new version is released, run:

```bash
composer update innoverng/ai-rules
```

The plugin compares each stub against your local copy:

- **Identical** → "Up to date"
- **Different** → Notifies you, points to the new stub, but does **not** overwrite

To force-copy all stubs and replace your local files, add this to your project's `composer.json` and run it manually when you choose:

```json
{
    "scripts": {
        "ai-rules:update": "Innoverng\\AiRules\\Installer::forceUpdate"
    }
}
```

```bash
composer ai-rules:update
```

## Releasing a New Version

1. Edit the stubs in `stubs/`
2. Commit and push
3. Tag the release:

```bash
git tag v1.1.0
git push origin v1.1.0
```

Consuming projects pick up the change on their next `composer update innoverng/ai-rules`.

## Note on `allow-plugins`

Composer 2.2+ requires explicit plugin trust. The `allow-plugins` config is required. Without it, Composer will prompt you interactively the first time, or skip the plugin silently in non-interactive (CI) environments.

## Manual composer.json snippet

If you prefer to edit `composer.json` yourself, add:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/innoverng/ai-rules"
        }
    ],
    "require": {
        "innoverng/ai-rules": "^1.0"
    },
    "config": {
        "allow-plugins": {
            "innoverng/ai-rules": true
        }
    }
}
```

Then run `composer update`.
