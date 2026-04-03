# Foundation Module

Foundation module for Pixielity Laravel headless API backend. Provides custom Application class, root route handlers, and base API structure.

## Features

- Custom `Application` class extending Laravel's Application with `App\` namespace
- Root route handlers returning 401 for unauthorized access
- Dynamic API versioning support (v1, v2, v3, etc.)
- Separate web and API route handling
- Production-ready configuration

## Routes

### Web Routes
- `GET /` - Returns 401 (unauthorized)

### API Routes
- `GET /api/` - Returns 401 (unauthorized)
- `GET /api/{version}` - Returns 401 (unauthorized) where version matches `v[0-9]+` (e.g., v1, v2, v10)

## Installation

This module is included by default in the Pixielity Laravel template.

## Usage

The foundation module automatically registers routes and provides the custom Application class. No additional configuration is required.

## License

MIT
