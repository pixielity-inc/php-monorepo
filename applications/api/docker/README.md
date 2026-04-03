# Docker Setup Guide

## Quick Start

### Using Composer Scripts (Recommended)

```bash
# Check if ports are available
composer docker:check

# Start containers (with port checking)
composer docker:up

# Start containers (kill conflicting processes)
composer docker:up:kill

# Start with OrbStack domains (macOS only)
composer docker:up:orbstack

# Stop containers
composer docker:down

# Stop and remove all data
composer docker:down:clean

# View logs
composer docker:logs

# View running containers
composer docker:ps

# Restart containers
composer docker:restart
```

### Using Scripts Directly

```bash
# Check ports
./bin/docker-check-ports
./bin/docker-check-ports --kill              # Kill conflicts
./bin/docker-check-ports --kill --yes        # Kill without confirmation

# Start containers
./bin/docker-up
./bin/docker-up --kill                       # Kill conflicts and start
./bin/docker-up --orbstack                   # Use OrbStack (macOS)
./bin/docker-up --skip-check                 # Skip port checking

# Stop containers
./bin/docker-down
./bin/docker-down --volumes                  # Remove volumes (deletes data!)
```

## Available Services

| Service | Port | URL | Description |
|---------|------|-----|-------------|
| Application | 8000 | http://localhost:8000 | Laravel app |
| PostgreSQL | 5432 | localhost:5432 | Database |
| pgAdmin | 5050 | http://localhost:5050 | Database UI |
| Redis | 6379 | localhost:6379 | Cache/Queue |
| MinIO | 9000 | http://localhost:9000 | S3 Storage |
| MinIO Console | 9001 | http://localhost:9001 | Storage UI |
| Meilisearch | 7700 | http://localhost:7700 | Search Engine |
| Mailpit | 8025 | http://localhost:8025 | Email Testing |
| Mailpit SMTP | 1025 | localhost:1025 | SMTP Server |

## Configuration

### Environment Files

- `docker/.env.docker` - Docker infrastructure settings (ports, container names)
- `.env` - Laravel application settings

### Enabling Optional Services

Edit `docker/docker-compose.yml` and uncomment the services you need:

```yaml
# Optional services (uncomment to enable):
  # - path: services/compose.mysql.yml
  # - path: services/compose.mongodb.yml
  # - path: services/compose.elasticsearch.yml
  # - path: services/compose.kafka.yml
  # - path: services/compose.rabbitmq.yml
```

## Port Conflicts

If you get port conflicts:

1. **Check which ports are in use:**
   ```bash
   composer docker:check
   ```

2. **Automatically kill conflicting processes:**
   ```bash
   composer docker:up:kill
   ```

3. **Manually kill specific process:**
   ```bash
   kill -9 <PID>
   ```

## OrbStack (macOS)

If using OrbStack on macOS, you can access services via domains:

```bash
# Start with OrbStack domains
composer docker:up:orbstack

# Access services
http://laravel.local        # Application
http://pgadmin.local        # pgAdmin
http://mailpit.local        # Mailpit
http://minio.local          # MinIO Console
```

Configure domains in `docker/.env.docker`:
```env
DOMAIN_LTD=.local
APP_DOMAIN=laravel
PGADMIN_DOMAIN=pgadmin
```

## Troubleshooting

### Containers won't start

1. Check ports:
   ```bash
   composer docker:check
   ```

2. Check Docker is running:
   ```bash
   docker ps
   ```

3. View logs:
   ```bash
   composer docker:logs
   ```

### Reset everything

```bash
# Stop and remove all containers and volumes
composer docker:down:clean

# Start fresh
composer docker:up
```

### Permission issues

```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache
```

## Development Workflow

### Initial Setup

```bash
# 1. Copy environment files
cp .env.example .env

# 2. Start Docker containers
composer docker:up

# 3. Install dependencies
composer install

# 4. Generate app key
php artisan key:generate

# 5. Run migrations
php artisan migrate
```

### Daily Development

```bash
# Start containers
composer docker:up

# Work on your code...

# View logs
composer docker:logs

# Stop containers (keeps data)
composer docker:down
```

### Clean Restart

```bash
# Stop and remove everything
composer docker:down:clean

# Start fresh
composer docker:up

# Re-run migrations
php artisan migrate:fresh --seed
```

## Platform-Specific Notes

### macOS
- Use Docker Desktop or OrbStack
- OrbStack is faster and uses less resources
- Use `composer docker:up:orbstack` for domain support

### Linux
- Native Docker support
- Best performance
- Use `composer docker:up`

### Windows
- Use Docker Desktop with WSL2
- Run commands in WSL2 terminal
- Line endings handled by `.gitattributes`

## Advanced Usage

### Custom Compose Files

```bash
# Use specific compose file
docker-compose -f docker/docker-compose.yml -f docker/services/compose.mysql.yml up -d
```

### Build Application Container

```bash
composer docker:build
```

### View Container Stats

```bash
docker stats
```

### Execute Commands in Container

```bash
# Run artisan command
docker-compose -f docker/docker-compose.yml exec app php artisan migrate

# Access shell
docker-compose -f docker/docker-compose.yml exec app bash
```

## Support

For issues or questions:
1. Check logs: `composer docker:logs`
2. Check port conflicts: `composer docker:check`
3. Try clean restart: `composer docker:down:clean && composer docker:up`
