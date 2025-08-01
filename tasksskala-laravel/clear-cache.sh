#!/bin/bash

echo "Limpando caches do Laravel..."

php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "Caches limpos com sucesso!"

echo ""
echo "URLs disponíveis para webhook:"
echo "1. https://intranet.skalacode.com/webhook/whatsapp"
echo "2. https://intranet.skalacode.com/api/webhook-whatsapp"
echo ""
echo "Testando webhook principal..."
curl -X POST https://intranet.skalacode.com/webhook/whatsapp \
-H "Content-Type: application/json" \
-d '{"event":"test","instance":"test","data":{}}'

echo ""
echo ""
echo "Testando webhook alternativo..."
curl -X POST https://intranet.skalacode.com/api/webhook-whatsapp \
-H "Content-Type: application/json" \
-d '{"event":"test","instance":"test","data":{}}'