# Environment Configuration

This directory contains environment configuration files for the Laravel application.

## Files

- `.env.example` - Example Laravel application configuration (tracked in git)
- `.env.docker.example` - Example Docker infrastructure configuration (tracked in git)
- `.env` - Actual Laravel application configuration (ignored by git)
- `.env.docker` - Actual Docker infrastructure configuration (ignored by git)

## Setup

### Quick Setup

Run the automated setup script:
```bash
composer setup:env
# or directly
php bin/setup-env
```

This will:
1. Copy `environments/.env.example` → `environments/.env`
2. Copy `environments/.env.docker.example` → `environments/.env.docker`
3. Create symlink: `.env` → `environments/.env`
4. Create symlink: `docker/.env.docker` → `../environments/.env.docker`

### Manual Setup

If you prefer manual setup:

1. Copy example files to create your environment files:
   ```bash
   cp environments/.env.example environments/.env
   cp environments/.env.docker.example environments/.env.docker
   ```

2. Update the values in `.env` and `.env.docker` for your environment

3. Symlinks are automatically created:
   - `laravel-template/.env` → `environments/.env`
   - `laravel-template/docker/.env.docker` → `../environments/.env.docker`

### File Purposes

#### .env (Application Configuration)
Contains Laravel application settings:
- Database credentials (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Cache/Redis credentials (REDIS_HOST, REDIS_PASSWORD)
- Mail settings (MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD)
- API keys and secrets (APP_KEY, AWS_*, STRIPE_*, etc.)
- Application environment (APP_ENV, APP_DEBUG, APP_URL)
- Queue settings (QUEUE_CONNECTION)
- Search engine credentials (MEILISEARCH_KEY, ELASTICSEARCH_PASSWORD)
- Storage credentials (AWS_*, MINIO_*)
- Broadcasting settings (REVERB_APP_ID, REVERB_APP_KEY, REVERB_APP_SECRET)

#### .env.docker (Docker Infrastructure)
Contains Docker container configuration:
- Container names and hostnames
- Port mappings
- Domain names (for OrbStack)
- Resource limits (CPU, memory)
- Network settings
- Volume drivers
- Build arguments (PHP_VERSION, NODE_VERSION)
- Health check settings
- Logging configuration

## Docker Compose Usage

Docker services load both files via `env_file` directive:

```yaml
services:
  app:
    env_file:
      - ../environments/.env.docker  # Docker infrastructure
      - ../.env                       # Application configuration
```

This separation ensures:
- Application secrets stay in `.env`
- Infrastructure config stays in `.env.docker`
- Both files are ignored by git
- Example files are tracked for reference

## Security

- Never commit actual `.env` or `.env.docker` files to git
- Keep sensitive credentials secure
- Use different values for development, staging, and production
- Consider using Docker secrets or vault solutions for production
