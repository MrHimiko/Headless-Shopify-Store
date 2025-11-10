#!/bin/bash

echo "===================================="
echo "Shopify Headless - Docker Deployment"
echo "===================================="
echo ""

if [ ! -f .env ]; then
    echo "❌ .env file not found!"
    echo "Creating from .env.example..."
    cp .env.example .env
    echo "✅ .env created. Please edit it with your Shopify credentials:"
    echo "   nano .env"
    echo ""
    echo "Then run this script again."
    exit 1
fi

echo "📦 Pulling latest images..."
docker-compose pull

echo ""
echo "🔨 Building containers..."
docker-compose build --no-cache

echo ""
echo "🚀 Starting services..."
docker-compose up -d

echo ""
echo "⏳ Waiting for services to be ready..."
sleep 10

echo ""
echo "🗄️  Running database migrations..."
docker exec -it shopify_backend php bin/console doctrine:migrations:migrate --no-interaction

echo ""
echo "✅ Deployment complete!"
echo ""
echo "Services running:"
docker-compose ps
echo ""
echo "Access your store at: http://localhost"
echo "Backend API: http://localhost/api"
echo ""
echo "View logs: docker-compose logs -f"
echo "Stop services: docker-compose down"
