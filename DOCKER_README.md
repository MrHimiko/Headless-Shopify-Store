# Docker Setup for Shopify Headless Store

Complete Docker configuration for deploying your Symfony + Nuxt headless Shopify store.

## What's Included

- **Nginx** - Web server routing traffic
- **PHP-FPM 8.2** - Symfony backend
- **Node 20** - Nuxt frontend (SSR)
- **PostgreSQL 16** - Database
- **Redis 7** - Caching layer

## File Structure

```
.
├── docker-compose.yml           # Main orchestration file
├── .env.example                 # Environment template
├── deploy.sh                    # One-click deployment script
├── DEPLOYMENT.md                # Full deployment guide
│
├── backend/
│   ├── Dockerfile               # PHP backend image
│   └── .dockerignore
│
├── frontend/
│   ├── Dockerfile               # Node frontend image
│   └── .dockerignore
│
└── docker/
    ├── nginx/
    │   ├── nginx.conf           # Nginx main config
    │   └── conf.d/
    │       └── default.conf     # Site routing config
    └── php/
        └── php.ini              # PHP settings
```

## Quick Deploy (New Server)

```bash
# Install Docker
apt update && apt install -y docker.io docker-compose

# Clone project
git clone your-repo /var/www/shopify-store
cd /var/www/shopify-store

# Configure
cp .env.example .env
nano .env  # Add your Shopify credentials

# Deploy
chmod +x deploy.sh
./deploy.sh
```

Done! Your store runs at `http://localhost`

## Manual Deploy

```bash
# Configure environment
cp .env.example .env
nano .env

# Start containers
docker-compose up -d

# Run migrations
docker exec -it shopify_backend php bin/console doctrine:migrations:migrate
```

## Environment Variables

Required in `.env`:

```bash
# Backend
APP_SECRET=generate-random-32-chars
POSTGRES_DB=shopify_store
POSTGRES_USER=shopify_user
POSTGRES_PASSWORD=strong-password

# Shopify
SHOPIFY_STORE_DOMAIN=your-store.myshopify.com
SHOPIFY_API_VERSION=2025-10
SHOPIFY_STOREFRONT_PRIVATE_TOKEN=shpss_xxxxx
SHOPIFY_STOREFRONT_PUBLIC_TOKEN=shpat_xxxxx
SHOPIFY_ADMIN_API_TOKEN=shpat_xxxxx
```

## Common Commands

```bash
# View all logs
docker-compose logs -f

# View specific service
docker-compose logs backend -f

# Restart services
docker-compose restart

# Stop everything
docker-compose down

# Rebuild after code changes
docker-compose up -d --build

# Execute command in backend
docker exec -it shopify_backend php bin/console cache:clear

# Access database
docker exec -it shopify_postgres psql -U shopify_user shopify_store
```

## Multi-Store Deployment

Same code, different `.env`:

**Server 1 (Store A):**
```bash
/var/www/store-a/
└── .env  # Store A credentials
```

**Server 2 (Store B):**
```bash
/var/www/store-b/
└── .env  # Store B credentials
```

Run `docker-compose up -d` in each directory.

## Ports

Default ports (can be changed in docker-compose.yml):

- **80** - HTTP (Nginx)
- **443** - HTTPS (Nginx, needs SSL setup)
- **5432** - PostgreSQL (internal only)
- **6379** - Redis (internal only)
- **9000** - PHP-FPM (internal only)
- **3000** - Nuxt (internal only)

## Volumes

Persistent data stored in:

- `postgres_data` - Database
- `redis_data` - Cache
- `backend_vendor` - PHP dependencies
- `frontend_node_modules` - Node dependencies

## Troubleshooting

**Containers won't start:**
```bash
docker-compose logs [service-name]
```

**Permission errors:**
```bash
docker-compose down
docker volume prune
docker-compose up -d --build
```

**Port already in use:**
Change ports in `docker-compose.yml`:
```yaml
nginx:
    ports:
        - "8080:80"  # Changed from 80
```

## Production Checklist

- [ ] Set `APP_ENV=prod`
- [ ] Strong passwords in `.env`
- [ ] All Shopify tokens configured
- [ ] SSL certificate installed
- [ ] Backups configured
- [ ] Firewall configured (ports 80, 443, 22)

## Backup & Restore

**Backup database:**
```bash
docker exec shopify_postgres pg_dump -U shopify_user shopify_store > backup.sql
```

**Restore database:**
```bash
cat backup.sql | docker exec -i shopify_postgres psql -U shopify_user shopify_store
```

## Need Help?

See `DEPLOYMENT.md` for detailed deployment instructions and troubleshooting.
