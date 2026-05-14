#!/bin/bash
# Deployment script stub for Docker container startup
# This script is called by Dockerfile-nginx-local CMD
# It runs environment-specific deployment scripts if they exist

set -e

echo "=== Running deployment script ==="

# Check if deployments directory exists and has scripts
if [ -d "./deployments" ]; then
    DEPLOYMENT_LOG="./deployments/deployment_complete.txt"
    
    for script in ./deployments/*.sh; do
        if [ -f "$script" ]; then
            SCRIPT_NAME=$(basename "$script")
            
            # Skip if already completed
            if [ -f "$DEPLOYMENT_LOG" ] && grep -Fxq "$SCRIPT_NAME" "$DEPLOYMENT_LOG" 2>/dev/null; then
                echo "Skipping $SCRIPT_NAME (already completed)"
                continue
            fi
            
            echo "Running deployment script: $SCRIPT_NAME"
            chmod +x "$script"
            if "$script"; then
                echo "$SCRIPT_NAME" >> "$DEPLOYMENT_LOG"
                echo "Completed: $SCRIPT_NAME"
            else
                echo "Warning: $SCRIPT_NAME failed, continuing..."
            fi
        fi
    done
else
    echo "No deployments directory found, skipping deployment scripts"
fi

echo "=== Deployment script completed ==="
