# Inventory MS — Agent Guide

Updated 2026-08-03 from the current repository tree. The repository is authoritative when this file or an Obsidian note disagrees with routes, migrations, components, or runtime checks.

## Project intent

Inventory MS (`InvMS`) is a Laravel inventory and movable-asset lending demo for three user roles:

- `admin`: full operational access, user administration, asset administration, and destructive actions.
- `staff`: inventory and asset workflow access, without admin-only controls.
- `user`: authenticated requester access for submitting and tracking asset requests.

Unauthenticated visitors can submit guest asset requests. This is a local/demo application, not a production asset-control system. It has no external payment, cloud storage, queue worker, or API surface beyond Laravel's normal web routes.

## Stack and boundaries

| Area | Implementation |
|---|---|
| Runtime | PHP 8.3+, Laravel 13 |
| UI | Livewire 4 full-page components, Blade, Alpine-style directives |
| Styling | Tailwind CSS 4, custom CSS tokens, `@tailwindcss/forms` |
| Build | Vite 8, Laravel Vite plugin, Tailwind Vite plugin |
| Icons | `@phosphor-icons/web` |
| PDF | `barryvdh/laravel-dompdf` |
| Database | SQLite by default in `.env.example`; MySQL/PostgreSQL/SQL Server configs remain available |
| Sessions | Database sessions by default |
| Mail | Log mailer by default; guest submissions notify admin users through Laravel notifications |
| Tests | PHPUnit is configured, but the repository currently contains no test cases |

Do not add repositories, services, DTOs, events, jobs, interfaces, or new packages for a single use case. Existing Livewire-to-Eloquent flows are intentional.

## Start here before changing code

1. Read `routes/web.php` and `routes/auth.php` to identify the route, middleware, and role boundary.
2. Read the routeable Livewire class in `app/Livewire` and its paired view in `resources/views/livewire`.
3. Read every model relationship and migration touched by the flow.
4. Search every caller and Blade action before changing shared behavior.
5. Reuse existing layouts, Blade components, validation attributes, model relationships, status values, and redirect/flash patterns.
6. Run the smallest check that proves the change, then run the broader gates required below.

## Source of truth

- Active routes and middleware: `routes/web.php`, `routes/auth.php`, and `bootstrap/app.php`.
- Role policy: `app/Http/Middleware/CheckRoleMiddleware.php`, `app/Providers/AppServiceProvider.php`, and the `@can` checks in views.
- Database schema: `database/migrations/`; do not infer schema from the local database file.
- Demo state: `database/seeders/DatabaseSeeder.php`.
- Inventory behavior: `app/Livewire/Inventory/*`, `app/Models/Stock.php`, `app/Models/Index.php`.
- Asset-lending behavior: `app/Livewire/Asset/*`, `app/Models/Asset.php`, `app/Models/Application.php`.
- Authentication/profile behavior: `routes/auth.php`, `resources/views/livewire/pages/auth`, and `resources/views/livewire/profile`.
- Shared UI: `resources/views/layouts`, `resources/views/components`, `resources/css/app.css`, and `resources/js/app.js`.
- Dependency/build contract: `composer.json`, `package.json`, `vite.config.js`, and `tailwind.config.js`.

## Essential commands

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
npm run dev
```

PowerShell:

```powershell
Copy-Item .env.example .env
```

Use `php artisan migrate:fresh --seed` only against a disposable database because it drops application tables.

Verification:

```bash
php artisan test
npm run build
php artisan view:cache
php artisan route:list --except-vendor
composer validate --no-check-publish
git diff --check
```

There is no JavaScript lint or test script in `package.json`. Do not invent one in change instructions.

## Architecture map

```text
app/
├── Http/Controllers/             Base controller and email verification endpoint
├── Http/Middleware/              Role gate for operational routes
├── Livewire/
│   ├── Actions/                  Logout action
│   ├── Asset/                    Guest, requester, submission, listing, record pages
│   ├── Dashboard/                Operational analytics
│   ├── Forms/                    Login form object and throttling
│   └── Inventory/                Entry, listing, record pages
├── Models/                       User, Stock, Index, Asset, Application
├── Notifications/                Guest submission admin email
├── Providers/                    Gates and framework provider
└── View/Components/              App layout and flash message component

bootstrap/                        Laravel application bootstrap
config/                           Framework and Livewire configuration
database/migrations/              Schema history
database/factories/               Test/development factories
database/seeders/                 Deterministic demo dataset
public/vendor/livewire/           Published Livewire browser assets
resources/css/app.css             Tailwind imports, tokens, shared utilities
resources/js/app.js               Phosphor icons and persisted light/dark theme
resources/views/                  Layouts, Blade components, pages, and Livewire views
routes/                            Web, auth, and console routes
tests/                             Empty Unit/Feature placeholders only
```

The normal request path is route → middleware → full-page Livewire component → Eloquent query/mutation → paired Blade view → Livewire-injected assets. The dashboard and profile are Blade pages that mount Livewire children.

## Route and access invariants

### Public

- `/` redirects to `/login`.
- `/guest/request` renders `Asset\Guest` and accepts guest lending requests.
- `/guest/index` renders `Asset\Index` and publicly lists pending applications.
- Guest login, password-reset, and reset-password routes are defined in `routes/auth.php`.

### Authenticated and verified

- `/asset/request` renders `Asset\Request` for role `user` requesters.
- `/profile` renders profile/password/account-management screens for all authenticated roles.

### Authenticated, verified, and admin/staff

The `check.role` middleware allows only `admin` or `staff`; other roles are redirected to `/asset/request`:

- `/dashboard`
- `/inventory/entry`
- `/inventory/listing`
- `/inventory/records/{id}`
- `/asset/submission`
- `/asset/listing`
- `/asset/records/{id}`

The middleware protects the route group, but Livewire action methods still receive public IDs and must be treated as untrusted input. Hiding a button with `@can` is not an authorization boundary.

## Domain invariants

### Stock and inventory

- `stocks.balance` is the current quantity.
- `indexes` records each stock movement and stores a snapshot `balance` after that movement.
- An entry must contain either `in_quantity` or `out_quantity`; outgoing stock cannot exceed the current balance.
- Receiving increases balance; issuing decreases balance.
- Deleting an index recalculates the stock balance and adjusts later index snapshots.
- Stock and index PDFs are generated with Dompdf from `resources/views/livewire/*/export-pdf.blade.php`.

### Assets and applications

- `assets.is_available` controls whether an asset can be issued.
- Application status values currently used by code are `0 = pending`, `1 = issued/approved`, and `3 = returned`.
- `Asset\Submission::approve()` assigns an available asset, records issuer/date, marks the application as `1`, and marks the asset unavailable in a transaction.
- `Asset\Submission::receive()` marks the asset available and the application returned in a transaction.
- `Asset\Submission::revert()` releases the asset and resets the application to pending.
- User requests are linked through `user_id`; guest requests use `guest_name` and `guest_email` with a nullable `user_id`.
- Admins and staff review operational submissions; only admins get admin-only creation/deletion controls in the current UI.

### Authentication and profiles

- User roles are stored as strings: `admin`, `staff`, `user`.
- Seeded users are email-verified so demo login reaches the application immediately.
- Changing a profile email clears `email_verified_at`; verified-only routes then require verification again.
- Admin profile screens can create admin/staff accounts and view/delete registered users.
- The login page includes buttons that authenticate the seeded demo accounts.

## Change guidance

### Add or change a route

Read route middleware and the paired component first. Add fixed routes to `routes/web.php`, name them, run `php artisan route:list --except-vendor`, and verify the intended role plus a denied role. Keep public guest routes deliberately scoped; do not expose operational pages by removing middleware.

### Change inventory behavior

Trace `Inventory\Entry` → `Stock`/`Index` → `inventory/entry.blade.php`, then trace `Inventory\Record` and its deletion/export views. Preserve balance snapshots, insufficient-stock checks, pagination, and the PDF view. If concurrency or audit correctness matters, use a database transaction and row lock rather than adding caller-side guards.

### Change asset lending

Trace `Asset\Request` or `Asset\Guest` → `Application` → `Asset\Submission` → asset/request/record views. Preserve the user/guest split, application dates, handler/receiver fields, status values, and asset availability transitions. Keep approve/receive/revert mutations atomic.

### Change authentication/profile

Trace `routes/auth.php`, the inline Livewire auth pages, `LoginForm`, `User`, `VerifyEmailController`, and profile views together. Preserve rate limiting, password hashing, signed verification URLs, session regeneration, CSRF, and verified middleware.

### Change Blade/CSS/JS

Change a Livewire class and paired view together when state moves. Reuse existing components and CSS tokens. Preserve labels, focus-visible styles, `x-cloak`, reduced-motion rules, and `wire:navigate`. Run `npm run build` after frontend changes.

### Change schema

Add a forward migration. Do not rewrite historical migrations for an installed database. Update models, factories, the seeder, views/components, and documentation together. The 2026 guest-request migration contains driver-specific foreign-key/column-change handling; keep upgrade behavior in mind.

## Security and correctness boundaries

- Do not commit `.env`, credentials, generated caches, `vendor/`, `node_modules/`, `public/build/`, or runtime storage.
- Validate all Livewire public properties and route/action IDs at the mutation boundary.
- Keep CSRF, auth, verified, signed URL, throttle, and password-confirmation protections intact.
- Guest requests are publicly reachable and currently send admin notifications through the configured mail channel; the default `.env.example` mailer is `log`.
- `/guest/index` publicly exposes pending request data. Treat this as a known demo boundary before deploying publicly.
- Stock balance mutations and deletion recalculation are not generally protected by row locks; concurrent writes can lose updates.
- Some mutation methods use `findOrFail`/`destroy` by ID without an explicit ownership or role check inside the method. The route middleware and UI gates currently carry most access control; tighten action-level authorization before exposing components differently.
- Application status and role values are magic strings/integers shared across PHP and Blade. Change them only as a coordinated migration/model/UI update.
- The PDF response uses user/asset data; keep Blade escaping and output filenames safe when changing exports.

## Do not edit or commit

- `.env` and credential-bearing files.
- `vendor/`, `node_modules/`, `public/build/`, and generated Livewire assets unless the task explicitly targets them.
- `bootstrap/cache/`, `storage/framework/`, PHPUnit caches, and runtime logs.
- Existing unrelated working-tree changes. The navigation Blade files may already contain user UI work; preserve it while changing adjacent documentation or behavior.

## Definition of done

1. The requested change works at the shared root and does not add speculative layers.
2. Role, validation, stock-balance, asset-availability, and guest-request invariants still hold.
3. A focused regression check exists for new non-trivial logic, or the handoff explains why the repository's empty test suite could not provide one.
4. Relevant PHP lint, Blade cache, frontend build, route, and Composer checks pass.
5. `README.md` and the relevant Obsidian note are updated when commands, routes, roles, schema, or demo behavior change.
6. Secrets, generated files, and unrelated changes are absent from the diff.

## Related project notes

- [[00 Project Overview]]
- [[01 Architecture]]
- [[02 Data Model]]
- [[03 Inventory and Asset Workflows]]
- [[04 Backend Reference]]
- [[05 Frontend Reference]]
- [[06 Operations and Development]]
- [[07 Testing and Known Issues]]
- [[08 Repository Map and Working Conventions]]
