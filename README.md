# php-monorepo

A production-ready PHP monorepo powered by [Turborepo](https://turborepo.dev).

[![CI](https://github.com/your-org/php-monorepo/actions/workflows/ci.yml/badge.svg)](https://github.com/your-org/php-monorepo/actions/workflows/ci.yml)
[![Security](https://github.com/your-org/php-monorepo/actions/workflows/security.yml/badge.svg)](https://github.com/your-org/php-monorepo/actions/workflows/security.yml)

---

## Structure

```
php-monorepo/
├── applications/          # Deployable apps
│   └── example-app/       # Laravel 13 application
├── modules/               # Shared PHP libraries (publishable to Packagist)
│   └── example_package/   # Example utility module
├── .github/
│   ├── workflows/
│   │   ├── ci.yml         # Lint + test on every PR / push
│   │   ├── cd_deploy.yml  # Deploy applications on main / version tags
│   │   ├── cd_publish.yml # Publish modules to Packagist on module tags
│   │   ├── security.yml   # Weekly dependency audit + CodeQL
│   │   └── release.yml    # GitHub Release on repo-level version tags
│   ├── ISSUE_TEMPLATE/
│   └── PULL_REQUEST_TEMPLATE/
├── turbo.json             # Root Turborepo pipeline
└── package.json           # npm workspaces root
```

---

## Quick start

```bash
# Install JS dependencies
npm install

# Install Composer dependencies for all workspaces
for dir in applications/*/  modules/*/; do
  [ -f "$dir/composer.json" ] && composer install --working-dir="$dir"
done

# Prepare the Laravel app
cp applications/example-app/.env.example applications/example-app/.env
php applications/example-app/artisan key:generate
php applications/example-app/artisan migrate

# Start everything in dev mode
npm run dev
```

---

## Common commands

| Command           | Description                                      |
|-------------------|--------------------------------------------------|
| `npm run build`   | Build all workspaces in dependency order         |
| `npm run dev`     | Start all workspaces in watch / dev mode         |
| `npm run test`    | Run all test suites                              |
| `npm run lint`    | Lint all workspaces                              |
| `npm run clean`   | Remove all build artefacts                       |

Filter to a single workspace:

```bash
npm run test -- --filter=@repo/example-package
npm run build -- --filter=@repo/example-app
```

---

## Adding a new application

```bash
laravel new applications/my-app --no-interaction
# Add a name field to applications/my-app/package.json
npm install
```

## Adding a new module

Copy `modules/example_package` as a template, update the namespace and
`composer.json` name, then run `npm install`.

## Publishing a module

```bash
# Bump version in modules/example_package/composer.json
git add . && git commit -m "chore: bump example_package to 1.2.3"
git tag example_package-v1.2.3
git push && git push --tags
```

The `cd_publish.yml` workflow handles the rest.

---

## Required GitHub secrets

| Secret               | Used by          | Description                              |
|----------------------|------------------|------------------------------------------|
| `CODECOV_TOKEN`      | ci.yml           | Codecov upload token                     |
| `DEPLOY_SSH_KEY`     | cd_deploy.yml    | Private SSH key for the deploy server    |
| `DEPLOY_HOST`        | cd_deploy.yml    | Deploy server hostname                   |
| `DEPLOY_USER`        | cd_deploy.yml    | SSH username                             |
| `DEPLOY_PATH`        | cd_deploy.yml    | Absolute path on server                  |
| `PACKAGIST_USERNAME` | cd_publish.yml   | Packagist username                       |
| `PACKAGIST_TOKEN`    | cd_publish.yml   | Packagist API token                      |

---

## License

MIT
