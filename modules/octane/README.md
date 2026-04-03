# Octane Module

Laravel Octane integration module with simplified server management commands for production-grade headless API backends.

## Features

- Simplified Octane server management commands
- Support for FrankenPHP and RoadRunner
- Production-ready configuration
- Easy start, stop, and restart commands

## Commands

### Start Server
```bash
php artisan app:start
```
Starts the Octane server using the configured driver (FrankenPHP or RoadRunner).

### Stop Server
```bash
php artisan app:stop
```
Stops the running Octane server.

### Restart Server
```bash
php artisan app:restart
```
Restarts the Octane server (stop + start).

## Installation

This module is included by default in the Pixielity Laravel template.

To enable the module:
```bash
php artisan module:enable octane
```

## Configuration

The module uses Laravel Octane's configuration file at `config/octane.php`. Configure your preferred server (FrankenPHP or RoadRunner) and settings there.

## Requirements

- PHP ^8.4
- Laravel ^13.0
- Laravel Octane ^2.17

## License

MIT
