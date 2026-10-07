# Deploying the shop

## Option A: Docker (easiest on a VPS)
1. Install Docker on the server and copy the project there.
2. `cp .env.example .env` and set: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://your-domain`,
   DB password, `MAIL_*`, `STRIPE_*`, and `CACHE_STORE=redis`, `QUEUE_CONNECTION=redis`, `SESSION_DRIVER=redis`.
3. `docker compose up -d --build`
4. `docker compose exec app php artisan key:generate --show` and put the result in `.env` as `APP_KEY`, then `docker compose up -d`.
5. Put a reverse proxy with HTTPS (Caddy, Nginx, Cloudflare) in front of port 8080.
6. In Stripe, add the webhook `https://your-domain/stripe/webhook`.

The `queue` and `scheduler` containers already run the background jobs.

## Option B: normal PHP hosting / VPS
1. PHP 8.2+ with `gd` (WebP), `intl`, `mbstring`, `zip`, `pdo_mysql`. Point the web root to `public/`.
2. `composer install --no-dev --optimize-autoloader`, `npm ci && npm run build`
3. `php artisan migrate --force`, `php artisan storage:link`, `php artisan optimize`
4. Cron (every minute): `* * * * * php /path/artisan schedule:run >> /dev/null 2>&1`
   This cancels unpaid orders, sends cart reminders and the low-stock alert, and makes the nightly backup.
5. Only if you use `QUEUE_CONNECTION=redis/database`: keep `php artisan queue:work` running (Supervisor).

## After every update
`php artisan migrate --force && php artisan optimize && php artisan queue:restart`

## Backups
`php artisan db:backup` saves a copy in `storage/app/backups` (14 days). Copy that folder to another place too
(another server, S3...), because a backup on the same disk is lost with the disk.

## Checklist before taking real money
- `APP_DEBUG=false`, HTTPS on, live Stripe keys, webhook secret set.
- Send a real test order, then refund it from the admin (Returns).
- Browse the whole shop with F12 open; when the console shows no CSP warnings, set `SECURITY_CSP_REPORT_ONLY=false`.
- Optional error alerts: `composer require sentry/sentry-laravel` and set `SENTRY_LARAVEL_DSN`.
