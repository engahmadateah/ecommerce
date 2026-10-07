# E-Commerce Store (Laravel 11 + Filament + Stripe)

Online store with cart, coupons, loyalty levels, packages, wishlist, reviews,
Stripe checkout (webhook-confirmed, stock-safe) and a Filament admin panel.

## Requirements
PHP 8.2+, Composer, Node 18+, MySQL (or SQLite for tests).

## Install
1. `composer install`
2. `npm install && npm run build`
3. `cp .env.example .env` then `php artisan key:generate`
4. Edit `.env`: database, `MAIL_*`, `MAIL_ADMIN_ADDRESS`, `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`
5. `php artisan migrate --seed`
6. `php artisan storage:link`
7. `php artisan serve`

## Admin
Open `/admin`. Only users with `role = admin` can enter.
Make a user admin: `php artisan tinker` then
`User::where('email','you@example.com')->update(['role'=>'admin']);`

## Stripe (local)
`stripe listen --forward-to localhost:8000/stripe/webhook` and copy the printed `whsec_...` into `STRIPE_WEBHOOK_SECRET`.

## Scheduled jobs (production)
Add one cron line: `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`
(it cancels unpaid orders and releases their stock).

## Go-live checklist
- `APP_ENV=production`, `APP_DEBUG=false`
- Real SMTP in `MAIL_*` (customers get order/shipping e-mails)
- Live Stripe keys + webhook endpoint `https://your-domain/stripe/webhook`
- `php artisan config:cache route:cache view:cache`
- Edit the policy texts in `config/policies.php` and the pages in admin Settings

## Tests
`php artisan test`

Checkout design notes: see `CHECKOUT-REFACTOR.md`.

## Deploying
See [DEPLOYMENT.md](DEPLOYMENT.md) (Docker or normal hosting, cron, backups, checklist).
