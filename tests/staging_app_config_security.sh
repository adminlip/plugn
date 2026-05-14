#!/bin/bash
# Test to verify staging app configs have no hardcoded credentials
# and debug/gii modules are disabled

set -e

CONFIG_DIR="environments/staging"
APPS="backend frontend partner agent api shortner remail crm"

echo "Testing staging app configurations..."

for app in $APPS; do
    config_file="$CONFIG_DIR/$app/config/main-local.php"
    
    if [ ! -f "$config_file" ]; then
        echo "ERROR: Missing config file: $config_file"
        exit 1
    fi
    
    echo "Checking $app..."
    
    # Check that cookieValidationKey uses env vars (not hardcoded or empty)
    if grep -q "cookieValidationKey.*=>.*''" "$config_file"; then
        echo "ERROR: $app has empty cookieValidationKey"
        exit 1
    fi
    
    if grep -qE "cookieValidationKey.*=>.*['\"][^\$']" "$config_file" 2>/dev/null; then
        echo "ERROR: $app has hardcoded cookieValidationKey"
        exit 1
    fi
    
    # Check that debug module is not enabled
    if grep -q "'debug'" "$config_file" && grep -q "yii\\\\debug\\\\Module" "$config_file"; then
        echo "ERROR: $app has debug module enabled (security risk in staging)"
        exit 1
    fi
    
    # Check that gii module is not enabled
    if grep -q "'gii'" "$config_file" && grep -q "yii\\\\gii\\\\Module" "$config_file"; then
        echo "ERROR: $app has gii module enabled (security risk in staging)"
        exit 1
    fi
    
    # Check that Redis hostname uses env vars (not hardcoded)
    if grep -q "'hostname'.*=>.*'plugn-redis" "$config_file"; then
        echo "ERROR: $app has hardcoded Redis hostname"
        exit 1
    fi
    
    # Verify env-local.php is included
    if ! grep -q "env-local.php" "$config_file"; then
        echo "ERROR: $app does not include env-local.php helper"
        exit 1
    fi
    
    echo "  ✓ $app config OK"
done

echo ""
echo "All staging app config security tests passed!"
