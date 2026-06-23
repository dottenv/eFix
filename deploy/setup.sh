#!/bin/bash
set -euo pipefail

DOMAIN="${1:-}"
if [ -z "$DOMAIN" ]; then
    echo "Usage: $0 <domain>"
    exit 1
fi

echo "=== eFix-php Deployment ==="
echo "Domain: $DOMAIN"

# Copy nginx config
sed "s/%DOMAIN%/$DOMAIN/g" nginx.conf > /etc/nginx/nginx.conf

# Get SSL cert
apt-get install -y certbot python3-certbot-nginx 2>/dev/null || true
certbot --nginx -d "$DOMAIN" --non-interactive --agree-tos --email admin@"$DOMAIN" 2>/dev/null || echo "SSL skipped"

# Replace with SSL config
sed "s/%DOMAIN%/$DOMAIN/g" nginx-ssl.conf > /etc/nginx/nginx.conf
systemctl reload nginx

echo "=== Done ==="
