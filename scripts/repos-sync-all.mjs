#!/usr/bin/env node
/**
 * scripts/repos-sync-all.mjs
 *
 * Runs `composer repos:sync` in every workspace that has a composer.json.
 * Called by the root package.json `preinstall` hook so that path repositories
 * are registered before `composer install` runs.
 *
 * Usage: node scripts/repos-sync-all.mjs
 */

import { execSync } from 'child_process';
import { existsSync, readdirSync } from 'fs';
import { join } from 'path';

const WORKSPACE_ROOTS = ['applications', 'modules'];

for (const dir of WORKSPACE_ROOTS) {
  if (!existsSync(dir)) continue;

  for (const workspace of readdirSync(dir)) {
    const wsPath = join(dir, workspace);
    const composerJson = join(wsPath, 'composer.json');

    if (!existsSync(composerJson)) continue;

    console.log(`\n📦 repos:sync → ${wsPath}`);

    try {
      execSync('composer repos:sync', { cwd: wsPath, stdio: 'inherit' });
    } catch {
      // Non-fatal — workspace may not have the script yet.
      console.warn(`  ⚠ repos:sync failed in ${wsPath} (skipping)`);
    }
  }
}
