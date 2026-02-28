#!/usr/bin/env bash
set -e

# Install innoverng/ai-rules via Composer (adds repo, allow-plugins, and require).
# Run from your project root: curl -sSL https://raw.githubusercontent.com/innoverng/ai-rules/HEAD/install.sh | bash

composer config repositories.ai-rules vcs https://github.com/innoverng/ai-rules
composer config allow-plugins.innoverng/ai-rules true
composer require innoverng/ai-rules
