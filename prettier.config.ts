/**
 * @file prettier.config.ts
 * @description Prettier configuration for the php-monorepo.
 *
 * Prettier handles formatting of all non-PHP files in this monorepo:
 *   - JavaScript / TypeScript
 *   - JSON / JSONC
 *   - Markdown
 *   - YAML (GitHub Actions workflows, melos.yaml, etc.)
 *
 * PHP files are formatted by Laravel Pint (pint.json), NOT Prettier.
 *
 * @see https://prettier.io/docs/en/configuration.html
 * @see https://prettier.io/docs/en/options.html
 *
 * Usage:
 *   Format all files:   npm run format
 *   Check only:         npm run format:check
 *   Single file:        npx prettier --write path/to/file.ts
 */

import type { Config } from "prettier";

/**
 * Root Prettier configuration object.
 *
 * All workspaces in this monorepo inherit these settings unless they
 * define their own `prettier.config.ts` that overrides specific options.
 */
const config: Config = {
  // ---------------------------------------------------------------------------
  // Quotes
  // ---------------------------------------------------------------------------

  /**
   * Use double quotes for strings.
   * Consistent with JSON (which always uses double quotes) and avoids
   * the need to escape apostrophes in English prose inside strings.
   */
  singleQuote: false,

  /**
   * Use double quotes in JSX attributes.
   * Matches HTML convention: <Component prop="value" />
   */
  jsxSingleQuote: false,

  // ---------------------------------------------------------------------------
  // Semicolons
  // ---------------------------------------------------------------------------

  /**
   * Always print semicolons at the end of statements.
   * Avoids ASI (Automatic Semicolon Insertion) edge cases.
   */
  semi: true,

  // ---------------------------------------------------------------------------
  // Trailing commas
  // ---------------------------------------------------------------------------

  /**
   * Add trailing commas wherever valid in ES5+ (objects, arrays, params).
   * Produces cleaner git diffs — adding a new item only shows one changed line
   * instead of two (the new item + the previous last item gaining a comma).
   */
  trailingComma: "all",

  // ---------------------------------------------------------------------------
  // Line length
  // ---------------------------------------------------------------------------

  /**
   * Soft line-length limit.
   * Prettier will try to keep lines under this length but won't break
   * strings or other non-breakable tokens to enforce it.
   */
  printWidth: 100,

  // ---------------------------------------------------------------------------
  // Indentation
  // ---------------------------------------------------------------------------

  /**
   * Use 2 spaces for indentation — matches .editorconfig and JS ecosystem
   * conventions. PHP files use 4 spaces (enforced by Pint, not Prettier).
   */
  tabWidth: 2,

  /**
   * Use spaces, not tabs. Tabs render differently across editors and tools.
   */
  useTabs: false,

  // ---------------------------------------------------------------------------
  // Arrow functions
  // ---------------------------------------------------------------------------

  /**
   * Always include parentheses around arrow function parameters.
   * Consistent style regardless of parameter count:
   *   (x) => x  rather than  x => x
   */
  arrowParens: "always",

  // ---------------------------------------------------------------------------
  // Line endings
  // ---------------------------------------------------------------------------

  /**
   * Use LF (Unix) line endings.
   * Consistent across macOS, Linux, and Windows (with Git's autocrlf).
   * Matches .editorconfig and .gitattributes settings.
   */
  endOfLine: "lf",

  // ---------------------------------------------------------------------------
  // Object formatting
  // ---------------------------------------------------------------------------

  /**
   * Print spaces between brackets in object literals.
   * { foo: bar }  rather than  {foo: bar}
   */
  bracketSpacing: true,

  /**
   * Put the closing `>` of a multi-line JSX element on a new line.
   * Improves readability of complex JSX trees.
   */
  bracketSameLine: false,

  // ---------------------------------------------------------------------------
  // Per-file-type overrides
  // ---------------------------------------------------------------------------

  overrides: [
    // -------------------------------------------------------------------------
    // JSON / JSONC
    // -------------------------------------------------------------------------
    {
      /**
       * JSON files must not have trailing commas — they are invalid JSON.
       * JSONC (JSON with Comments) technically allows them but many parsers
       * don't, so we disable them here for safety.
       */
      files: ["*.json", "*.jsonc"],
      options: {
        trailingComma: "none",
      },
    },

    // -------------------------------------------------------------------------
    // Markdown
    // -------------------------------------------------------------------------
    {
      /**
       * Wrap prose at 80 characters for readability in terminals and code
       * review tools. Use a narrower printWidth than the default 100.
       *
       * proseWrap: "always" — wrap long lines in Markdown prose.
       */
      files: ["*.md", "*.mdx"],
      options: {
        proseWrap: "always",
        printWidth: 80,
      },
    },

    // -------------------------------------------------------------------------
    // YAML
    // -------------------------------------------------------------------------
    {
      /**
       * YAML files (GitHub Actions, melos.yaml, pnpm-workspace.yaml).
       * Force double quotes for consistency with JSON and to avoid
       * ambiguity with YAML special characters in single-quoted strings.
       */
      files: ["*.yml", "*.yaml"],
      options: {
        singleQuote: false,
      },
    },

    // -------------------------------------------------------------------------
    // TypeScript
    // -------------------------------------------------------------------------
    {
      /**
       * TypeScript-specific overrides.
       * Explicit return types and strict formatting for .ts/.tsx files.
       */
      files: ["*.ts", "*.tsx"],
      options: {
        // TypeScript files use the same defaults — no overrides needed.
        // Add overrides here if TypeScript files need different formatting.
      },
    },
  ],
};

export default config;
