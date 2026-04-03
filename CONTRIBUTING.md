# Contributing

Thank you for taking the time to contribute. This document explains how to get
set up, the conventions we follow, and how to submit changes.

---

## Prerequisites

| Tool       | Minimum version | Install                          |
|------------|-----------------|----------------------------------|
| Node.js    | 18              | https://nodejs.org               |
| npm        | 10              | bundled with Node                |
| PHP        | 8.2             | https://php.net                  |
| Composer   | 2               | https://getcomposer.org          |
| Git        | 2.40            | https://git-scm.com              |

---

## Getting started

```bash
# 1. Clone the repo
git clone https://github.com/your-org/php-monorepo.git
cd php-monorepo

# 2. Install all JS dependencies (hoisted via npm workspaces)
npm install

# 3. Install Composer dependencies for every workspace
for dir in applications/*/  modules/*/; do
  [ -f "$dir/composer.json" ] && composer install --working-dir="$dir"
done

# 4. Copy .env for the example app
cp applications/api-app/.env.example applications/api-app/.env
php applications/api-app/artisan key:generate
php applications/api-app/artisan migrate

# 5. Start all workspaces in dev mode
npm run dev
```

---

## Workflow

1. Create a branch from `main`: `git checkout -b feat/my-feature`
2. Make your changes with tests and docblocks.
3. Run `npm run lint` and `npm run test` — both must pass.
4. Commit using [Conventional Commits](https://www.conventionalcommits.org):
   - `feat: add X`
   - `fix: correct Y`
   - `chore: update Z`
5. Open a Pull Request against `main`.

---

## Adding a new application

```bash
# Under applications/
laravel new applications/my-app --no-interaction
```

Add a `package.json` with a `name` field so Turborepo discovers it, then run
`npm install` from the repo root.

---

## Adding a new module

```bash
mkdir -p modules/my-module/src
# Add composer.json, package.json, turbo.json following modules/example_package as a template
```

---

## Publishing a module to Packagist

1. Bump the `version` in `modules/<name>/composer.json`.
2. Update `modules/<name>/CHANGELOG.md`.
3. Commit, push, then tag:

```bash
git tag example_package-v1.2.3
git push --tags
```

The `cd_publish.yml` workflow will run the CI gate and trigger a Packagist update automatically.

---

## Code style

- PHP: [PSR-12](https://www.php-fig.org/psr/psr-12/) — 4-space indent, strict types.
- JS/TS: Prettier defaults — 2-space indent.
- All files: EditorConfig rules in `.editorconfig`.
