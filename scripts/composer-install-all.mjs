#!/usr/bin/env node
/**
 * scripts/composer-install-all.mjs
 *
 * Runs `composer install` in every workspace that has a composer.json.
 * Called by the root package.json `postinstall` hook so that PHP dependencies
 * are installed after pnpm finishes installing node dependencies.
 *
 * Usage: node scripts/composer-install-all.mjs
 */

import { execSync } from 'child_process';
import { existsSync, readdirSync } from 'fs';
import { join } from 'path';

const WORKSPACE_ROOTS = ['applications', 'modules'];

const COMPOSER_FLAGS = [
  '--no-interaction',
  '--prefer-dist',
  '--optimize-autoloader',
].join(' ');

for (const dir of WORKSPACE_ROOTS) {
  if (!existsSync(dir)) continue;

  for (const workspace of readdirSync(dir)) {
    const wsPath = join(dir, workspace);
    const composerJson = join(wsPath, 'composer.json');

    if (!existsSync(composerJson)) continue;

    console.log(`\n🐘 composer install → ${wsPath}`);

    try {
      execSync(`composer install ${COMPOSER_FLAGS}`, {
        cwd: wsPath,
        stdio: 'inherit',
      });
    } catch {
      console.error(`  ✖ composer install failed in ${wsPath}`);
      process.exit(1);
    }
  }
}
