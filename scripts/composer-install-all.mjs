#!/usr/bin/env node
/**
 * scripts/composer-install-all.mjs
 *
 * Thin wrapper called by the root package.json `postinstall` hook.
 * Delegates to WorkspaceDiscovery::installAll via composer.
 *
 * The actual logic lives in scripts/WorkspaceDiscovery.php.
 */
import { execSync } from 'child_process';

try {
  execSync('composer install:all', { stdio: 'inherit' });
} catch (e) {
  console.error('✖ composer install:all failed');
  process.exit(1);
}
