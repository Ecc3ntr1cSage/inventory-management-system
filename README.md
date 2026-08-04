# Inventory MS

Inventory MS (`InvMS`) is a Laravel and Livewire inventory-management demo for stock movements, movable assets, and internal/guest asset-lending requests.

> Demo boundary: this is an internal operations prototype, not a production asset-control, audit, notification, or records-retention system.

## What it includes

- Role-based access for `admin`, `staff`, and `user` accounts.
- Stock receiving/issuing with current balances and movement history.
- Search, sorting, pagination, stock deletion, and stock-record PDF export.
- Asset catalogue with availability tracking and asset-record PDF export.
- Authenticated user asset requests with pending, issued, and returned states.
- Public guest asset requests with a honeypot field and admin email notifications.
- Admin/staff submission review: approve, receive, or revert requests.
- Dashboard analytics for stock, assets, and request counts.
- Profile, password, email verification, account deletion, and admin user management.
- Light/dark theme persistence and a Tailwind-based responsive interface.

The application does not provide a public API, payment flow, cloud media pipeline, queue worker, or production-grade audit/security controls.

## Technology

| Area | Implementation |
|---|---|
| Backend | PHP 8.3+, Laravel 13 |
| UI | Livewire 4, Blade, Alpine-style directives |
| Styling | Tailwind CSS 4, custom CSS tokens, `@tailwindcss/forms` |
| Assets | Vite 8 and Laravel Vite plugin |
| Icons | `@phosphor-icons/web` |
| PDFs | `barryvdh/laravel-dompdf` |
| Database | SQLite default in `.env.example`; MySQL, PostgreSQL, and SQL Server configs are retained |
| Sessions | Database sessions by default |
| Mail | Log mailer by default |
| Tests | PHPUnit configured; no test cases currently exist |

Exact dependency constraints are in [`composer.json`](composer.json) and [`package.json`](package.json).

## Quick start

### Requirements

- PHP 8.3+ with the Laravel-required extensions.
- Composer.
- Node.js and npm.
- SQLite for the default zero-service setup, or another configured database.

### Install

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

PowerShell:

```powershell
Copy-Item .env.example .env
```

The example environment uses SQLite, database sessions, database cache/queue settings, and log mail. Configure `.env` before migrating an existing database.

### Run locally

Start Laravel and Vite in separate terminals:

```bash
php artisan serve
npm run dev
```

Open `http://127.0.0.1:8000`. For a production asset build, run `npm run build` instead of `npm run dev`.

### Deploy to Render

Create a Render Web Service using the repository's `Dockerfile`. Set `APP_KEY` to a generated Laravel key and set `APP_URL` to the Render service URL. Render supplies `PORT` automatically.

The default SQLite database is stored inside the container and is lost when the service is redeployed. Use a Render persistent disk with `DB_DATABASE=/var/www/html/database/database.sqlite`, or configure an external MySQL/PostgreSQL database for persistent data.

After the first deploy, run `php artisan db:seed --force` once from the Render Shell to load the demo accounts and records.

To recreate the deterministic demo database:

```bash
php artisan migrate:fresh --seed
```

`migrate:fresh` is destructive and should only target a disposable database.

## Demo accounts

All seeded demo accounts are email-verified and use password `12345678`.

| Role | Email | Name |
|---|---|---|
| Admin | `admin@invms.test` | Pentadbir Demo |
| Staff | `staff@invms.test` | Pegawai Stor Demo |
| User | `user@invms.test` | Pemohon Demo |

The login page exposes one-click demo login buttons backed by `User::DEMO_ACCOUNTS`.

## Main journeys

### Guest asset request

1. Open `/guest/request` or select **Buat Permohonan** on the login page.
2. Enter guest identity and request details.
3. The hidden `website` honeypot silently ignores obvious bot submissions.
4. A valid request creates an `applications` row with a nullable `user_id`.
5. Admin users receive a Laravel mail notification through the configured mail channel.

Pending applications are also exposed by `/guest/index`; treat that route as a demo-only public boundary.

### User asset request

1. Sign in as the demo user.
2. Open `/asset/request`.
3. Submit the description, reason, position, department, and location.
4. The page shows the user's request history and status.

### Staff/admin asset issuance

1. Sign in as admin or staff.
2. Open `/asset/submission`.
3. Approve a pending request by selecting an available asset.
4. The application becomes issued (`status = 1`) and the asset becomes unavailable.
5. Receive the asset later to mark the application returned (`status = 3`) and release the asset.
6. Revert an issued decision to return the application to pending (`status = 0`).

### Inventory movement

1. Open `/inventory/entry` as admin or staff.
2. Select a stock item, reference number, date, and either receiving or issuing quantity.
3. Receiving increases `stocks.balance`; issuing decreases it.
4. Open the generated stock record page to search, sort, paginate, delete entries, or export a PDF.

## Route surface

| Route | Name | Access | Purpose |
|---|---|---|---|
| `/` | — | Public | Redirects to `/login` |
| `/login` | `login` | Guest | Livewire login and demo account shortcuts |
| `/forgot-password` | `password.request` | Guest | Send password reset link |
| `/reset-password/{token}` | `password.reset` | Guest | Reset password |
| `/verify-email` | `verification.notice` | Auth | Email verification notice |
| `/verify-email/{id}/{hash}` | `verification.verify` | Signed/auth | Mark email verified |
| `/confirm-password` | `password.confirm` | Auth | Confirm current password |
| `/guest/request` | `guest.request` | Public | Guest asset request form |
| `/guest/index` | `guest.index` | Public | Pending application listing |
| `/dashboard` | `dashboard` | Admin/staff + verified | Operational analytics |
| `/inventory/entry` | `inventory.entry` | Admin/staff + verified | Stock movement and stock creation |
| `/inventory/listing` | `inventory.listing` | Admin/staff + verified | Stock catalogue |
| `/inventory/records/{id}` | `inventory.record` | Admin/staff + verified | Stock movement history/PDF |
| `/asset/submission` | `asset.submission` | Admin/staff + verified | Review asset requests |
| `/asset/listing` | `asset.listing` | Admin/staff + verified | Asset catalogue |
| `/asset/records/{id}` | `asset.record` | Admin/staff + verified | Asset history/PDF |
| `/asset/request` | `asset.request` | Auth + verified | User request form/history |
| `/profile` | `profile` | Auth + verified | Profile and account settings |

Inspect the actual runtime route table with:

```bash
php artisan route:list --except-vendor
```

## Architecture

This is a conventional server-rendered Laravel monolith:

- `routes/` defines web and authentication entry points.
- Routeable Livewire components in `app/Livewire` own form state, validation, queries, mutations, and redirects.
- Paired Blade views in `resources/views/livewire` render each component.
- Eloquent models in `app/Models` represent users, stock, movements, assets, and applications.
- `database/migrations` is the schema history; `DatabaseSeeder` is the canonical demo fixture.
- Blade layouts and components provide the shell, navigation, modals, forms, flash messages, and PDF templates.
- `resources/css/app.css` defines warm neutral design tokens, component helpers, focus styles, and reduced-motion rules.
- `resources/js/app.js` imports Phosphor icon packs and persists the `invms-theme` light/dark preference.

Important files:

| File | Responsibility |
|---|---|
| `routes/web.php` | Active application routes and role groups |
| `routes/auth.php` | Auth and email-verification routes |
| `bootstrap/app.php` | Middleware alias registration and route bootstrap |
| `app/Http/Middleware/CheckRoleMiddleware.php` | Admin/staff operational gate |
| `app/Providers/AppServiceProvider.php` | `admin` and `staff` authorization gates |
| `app/Livewire/Inventory/Entry.php` | Stock creation and movements |
| `app/Livewire/Inventory/Record.php` | Stock history, deletion, and export |
| `app/Livewire/Asset/Submission.php` | Approve, receive, and revert lending applications |
| `app/Livewire/Asset/Guest.php` | Public guest request flow and notification |
| `app/Models/Application.php` | User/guest request persistence and relationships |
| `database/seeders/DatabaseSeeder.php` | Demo accounts and canonical records |
| `resources/css/app.css` | Theme tokens and shared UI primitives |
| `resources/js/app.js` | Theme and icon frontend entry |

## Data model

| Table | Purpose |
|---|---|
| `users` | Name, unique email, role, password, verification, session token |
| `stocks` | Stock name, group, location, current balance |
| `indexes` | Stock movement date, reference, in/out quantities, balance snapshot, operator name |
| `assets` | Asset name, model, registration number, availability |
| `applications` | Authenticated or guest request, request details, asset assignment, issue/return metadata, status |
| `password_reset_tokens` | Laravel password reset tokens |
| `sessions` | Database-backed sessions |

Relationships:

- `Stock hasMany Index` through `Stock::entries()`.
- `Index belongsTo Stock` through `Index::inventory()`.
- `Asset hasMany Application` through `Asset::applications()`.
- `Application belongsTo Asset` and optionally belongsTo User.
- Guest applications have `user_id = null` and retain `guest_name`/`guest_email`.

The application migration uses foreign keys that set deleted users to null and cascade deleted assets to their applications. Do not rewrite old migrations for an already-installed database; add a forward migration for schema changes.

## Development and verification

```bash
php artisan test
npm run build
php artisan view:cache
composer validate --no-check-publish
git diff --check
```

Current repository baseline checked on 2026-08-03:

- `npm run build`: passes.
- `php artisan view:cache`: passes.
- PHP lint across app, database, routes, and config: passes.
- Composer validation and optimized autoload/package discovery: passes.
- `php artisan test`: no tests found; PHPUnit is configured with `failOnEmptyTestSuite=false`.

## Known limitations

- There are no application feature or unit tests yet.
- `guest/index` publicly lists pending request data.
- Role and application statuses are strings/integers shared between PHP, Blade, and seed data rather than centralized enums.
- Several Livewire mutation methods rely on route middleware and UI gates rather than repeating role/record authorization inside every action.
- Stock balance updates and historical-balance recalculation are not protected by row locks, so concurrent writes may race.
- The inventory movement form's action validation uses numeric rules while its property attributes use integer rules; standardize this before relying on strict quantity semantics.
- Guest notifications depend on configured Laravel mail delivery; the example configuration only logs mail.
- The migrations retain framework defaults and a driver-sensitive guest-field/foreign-key migration; verify both fresh and upgrade paths when changing schema.
- The application has no production deployment, backup, audit-retention, or monitoring contract.

## Cleanup baseline

The recent cleanup removed orphaned scaffold code, unused starter views/components, Axios/PostCSS leftovers, unused Breeze/Sail dependencies, dead Livewire state, unused gates/theme helpers, and a phantom inventory price column. Existing navigation UI changes were preserved.

## Documentation

The complete developer notes are in the Dev Obsidian vault under `Projects/Inventory MS`:

- [[00 Project Overview]]
- [[01 Architecture]]
- [[02 Data Model]]
- [[03 Inventory and Asset Workflows]]
- [[04 Backend Reference]]
- [[05 Frontend Reference]]
- [[06 Operations and Development]]
- [[07 Testing and Known Issues]]
- [[08 Repository Map and Working Conventions]]

See [`AGENTS.md`](AGENTS.md) for coding-agent rules, invariants, change recipes, and safety boundaries.

## License

MIT, as declared in `composer.json`.
