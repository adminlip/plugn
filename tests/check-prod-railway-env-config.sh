#!/usr/bin/env bash
set -euo pipefail

files=(
  environments/prod-railway/common/config/main-local.php
  environments/prod-railway/backend/config/main-local.php
  environments/prod-railway/frontend/config/main-local.php
  environments/prod-railway/partner/config/main-local.php
)

for file in "${files[@]}"; do
  php -l "$file" >/dev/null
done

if grep -RInE "mysql-eemz\.railway\.internal|redis-xkt_\.railway\.internal" "${files[@]}"; then
  echo "prod Railway DB/Redis config must come from environment variables" >&2
  exit 1
fi

grep -q "MYSQLHOST" environments/prod-railway/common/config/main-local.php
grep -q "REDISHOST" environments/prod-railway/common/config/main-local.php
for app in backend frontend partner; do
  grep -q "REDISHOST" "environments/prod-railway/${app}/config/main-local.php"
done
