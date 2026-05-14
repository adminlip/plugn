# Local Docker Development Setup

## Prerequisites
- Docker & Docker Compose installed

## Quick Start

```bash
# Start all services
docker compose -f docker-compose-dev.yml up -d

# Wait for MySQL health check to pass, then init environment inside the container:
docker compose -f docker-compose-dev.yml exec app sh -c \
  "./init --env=Dev-Server-Docker --overwrite=All && ./yii migrate --interactive=0"
```

## Access Points (port-based, no DNS setup needed)

| Service  | URL                          |
|----------|------------------------------|
| Backend  | http://localhost:8080         |
| Agent    | http://localhost:8081         |
| API      | http://localhost:8082         |
| CRM      | http://localhost:8083         |
| Frontend | http://localhost:8084         |
| Partner  | http://localhost:8085         |
| Shortner | http://localhost:8086         |
| Remail   | http://localhost:8087         |

## Architecture

The `docker-compose-dev.yml` uses `Dockerfile-nginx-local` which loads `nginx/local.conf`
(port-based routing). The MySQL service includes a health check so the app container waits
until the database is ready before starting.

Session storage uses the Docker Redis service (`redis:6379`) instead of external AWS
ElastiCache endpoints. Override via environment variables:

```bash
REDIS_HOST=redis REDIS_PORT=6379
```

## Troubleshooting

```bash
# Check service status
docker compose -f docker-compose-dev.yml ps

# View logs
docker compose -f docker-compose-dev.yml logs -f app

# Rebuild after Dockerfile changes
docker compose -f docker-compose-dev.yml up --build
```