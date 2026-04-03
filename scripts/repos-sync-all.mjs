#!/usr/bin/env node
/**
 * scripts/repos-sync-all.mjs
 *
 * Thin wrapper called by the root package.json `preinstall` hook.
 * Delegates to WorkspaceDiscovery::reposSyncAll via composer.
 *
 * The actual logic lives in scripts/WorkspaceDiscovery.php.
 */
import { execSync } from 'child_process';

try {
  execSync('composer repos:sync:all', { stdio: 'inherit' });
} catch {
  // Non-fatal — composer may not be installed yet on first clone.
  console.warn('⚠ repos:sync:all skipped (composer not available)');
}
