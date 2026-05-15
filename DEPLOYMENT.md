# Plugn Deployment Guide

## Quick Start

```bash
# 1. Clone the repository
git clone https://github.com/BAWES-Universe/plugn.git
cd plugn

# 2. Create .env from template
cp .env.example .env
# Edit .env with your actual credentials
nano .env

# 3. Start all services
docker compose up -d

# 4. Verify health
docker compose ps
curl -f http://localhost:8080/health || echo "App not ready yet, wait ~30s"
```

## Architecture

| Service | Description | Port |
|---------|-------------|------|
| `app` | Nginx + PHP-FPM | 8080 (localhost only) |
| `mysql` | MySQL 8.0 database | 3306 (localhost only) |
| `redis` | Redis 7 cache | 6379 (localhost only) |
| `queue-worker` | Background queue listener | internal |

All ports bind to `127.0.0.1` for security. Use a reverse proxy (Nginx/Caddy) with TLS for public access.

## Scaling for 10k+ Stores

### Application Scaling
```bash
# Scale queue workers
docker compose up -d --scale queue-worker=3
```

### Database Optimization
- MySQL is configured with InnoDB and utf8mb4
- Schema caching enabled via `enableSchemaCache` in main-local.php
- For 10k+ stores, consider read replicas and connection pooling

### Redis Caching
- Redis is configured as cache backend and session handler
- OPcache enabled for PHP bytecode caching
- Consider Redis Cluster for high availability

### Nginx Tuning
- Gzip compression enabled
- Static asset caching (1-year expiry for fonts/images)
- Rate limiting configured for API endpoints
- Buffer sizes optimized for concurrent connections

## Environment Variables

See `.env.example` for all available configuration options.

**Required variables:**
- `MYSQL_ROOT_PASSWORD` - MySQL root password
- `MYSQL_PASSWORD` - Application database password
- `AWS_S3_KEY` / `AWS_S3_SECRET` - S3 file storage credentials
- `AWS_SQS_KEY` / `AWS_SQS_SECRET` - SQS queue credentials

## Monitoring

```bash
# View logs
docker compose logs -f app
docker compose logs -f queue-worker

# Check database
docker compose exec mysql mysql -u plugn -p plugn

# Check Redis
docker compose exec redis redis-cli ping
```

## Production Checklist

- [ ] Set strong passwords in `.env`
- [ ] Configure TLS reverse proxy (Caddy/Nginx) in front of app:8080
- [ ] Set up automated database backups
- [ ] Configure monitoring (Prometheus/Grafana or similar)
- [ ] Set up log aggregation
- [ ] Review and lock down Auth0/Google OAuth credentials
- [ ] Configure SMTP for transactional emails
- [ ] Set up AWS S3 bucket for file uploads
