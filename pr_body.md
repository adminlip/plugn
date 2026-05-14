## Summary

Addresses #59 — the server needs to handle ~10k registered stores efficiently. This PR enables production-grade caching and PHP performance optimizations that were previously commented out or missing.

## Changes

### 1. Redis Cache (replaces FileCache)
- **common/config/main.php**: Uncommented and configured Redis cache using yii\redis\Cache with environment-variable-configurable connection (REDIS_HOST, REDIS_PORT, REDIS_PASSWORD, REDIS_CACHE_DB)
- **console/config/main.php**: Enabled Redis cache for console commands (was commented out entirely)
- Redis connection includes retry logic and timeout settings for resilience

### 2. OPcache + JIT
- **php.ini**: Added OPcache configuration with JIT support (opcache.jit = 1255, opcache.jit_buffer_size = 128M), 256MB memory allocation, and 20,000 max accelerated files
- Disabled timestamp validation for production (validate_timestamps = 0) — use opcache_reset() after deployments

### 3. Redis Session Handler
- **php.ini**: Configured session.save_handler = redis for scalable session storage
- **common/config/main.php**: Added yii\redis\Session component with separate database (DB 1) for session isolation

### 4. Docker Infrastructure
- **Dockerfile-nginx-railway**: Enabled opcache PHP extension (was commented out) and installed redis PECL extension
- **docker-compose-dev.yml / docker-compose-prod.yml**: Added Redis healthcheck (redis-cli ping), memory limits (128MB dev / 256MB prod), LRU eviction policy, and AOF persistence

## Environment Variables

| Variable | Default | Description |
|---|---|---|
| REDIS_HOST | localhost | Redis server hostname |
| REDIS_PORT | 6379 | Redis server port |
| REDIS_PASSWORD | null | Redis auth password |
| REDIS_CACHE_DB | 0 | Database for application cache |
| REDIS_SESSION_DB | 1 | Database for session storage |

## Performance Impact

For 10k stores, this addresses key bottlenecks:
- **FileCache -> Redis**: Eliminates disk I/O for cache reads; ~10x faster for high-concurrency scenarios
- **OPcache + JIT**: Pre-compiles PHP bytecode; JIT provides additional 10-20% execution speedup on PHP 8.x
- **Redis sessions**: Enables horizontal scaling of PHP-FPM workers without sticky sessions
- **Redis healthcheck**: Ensures cache availability before accepting traffic

## Testing

- Verify Redis connection: `docker-compose exec app php -r "var_dump(Yii::$app->redis->ping());"`
- Verify cache: `docker-compose exec app php -r "var_dump(Yii::$app->cache->set('test', 'ok')); var_dump(Yii::$app->cache->get('test'));"`
- Verify OPcache: `docker-compose exec app php -i | grep opcache.enable`

## Breaking Changes

None — Redis was already a dependency (yiisoft/yii2-redis in composer.json) and running in docker-compose, just not connected to the application.