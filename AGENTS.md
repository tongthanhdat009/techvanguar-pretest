# AGENTS.md - Flashcard Learning Hub

This file provides essential context for AI coding agents working on this project.

## Project Overview

Flashcard Learning Hub is a **Laravel 11** flashcard learning platform with dual-portal architecture (Admin + Client), JWT-based API authentication, and spaced repetition study features.

### Key Features
- **Dual authentication systems**: Separate session-based web auth for admin/client portals + JWT for APIs
- **Spaced repetition algorithm**: Study scheduling with XP rewards and daily streak tracking
- **Deck management**: Public/private decks, CSV import/export, deck copying, reviews
- **Gamification**: Experience points, levels, daily streaks

---

## Technology Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 8.2+, Laravel 11 |
| Frontend | Blade + Tailwind CSS + Alpine.js (via global scripts) |
| Build Tool | Vite 6.x with Laravel Vite Plugin |
| Authentication | Session guards (web) + JWT (API) via `tymon/jwt-auth` |
| Database | SQLite (local/dev) or MySQL (staging/production) |
| Queue | Database driver |
| Cache | Database driver |
| Testing | PHPUnit 11 with SQLite in-memory |
| Code Style | Laravel Pint |

---

## Project Structure

```
app/
├── Auth/                    # Custom session guards
│   ├── AdminSessionGuard.php
│   └── ClientSessionGuard.php
├── Http/
│   ├── Controllers/
│   │   ├── Api/             # API controllers (JWT auth)
│   │   │   ├── AuthController.php
│   │   │   ├── Admin/       # Admin API CRUD
│   │   │   └── Client/      # Client API (decks, progress)
│   │   └── Web/             # Web controllers (session auth)
│   │       ├── AdminDashboardController.php
│   │       ├── AuthPageController.php
│   │       └── ClientPortalController.php
│   ├── Middleware/
│   │   ├── EnsureUserRole.php       # Role validation middleware
│   │   ├── SetSessionCookie.php     # Guard-specific session cookies
│   │   └── RedirectIfAuthenticated.php
│   └── Requests/            # Form request validation classes
│       ├── AdminDeckRequest.php
│       ├── ClientDeckRequest.php
│       ├── AdminFlashcardRequest.php
│       ├── ClientFlashcardRequest.php
│       ├── UpdateProfileRequest.php
│       ├── RecordStudyProgressRequest.php
│       └── Concerns/NormalizesTags.php
├── Models/
│   ├── User.php             # JWTSubject, roles, gamification
│   ├── Deck.php             # Visibility, tags, source_deck_id
│   ├── Flashcard.php
│   ├── StudyProgress.php    # Spaced repetition state
│   └── DeckReview.php
├── Services/                # Business logic layer
│   ├── StudyScheduler.php   # Core SRS algorithm
│   ├── StudySessionService.php
│   ├── CsvImportService.php
│   ├── CsvExportService.php
│   └── DeckCopyService.php
├── Support/
│   └── DeckAccess.php       # Deck authorization queries
└── Providers/
    ├── AuthServiceProvider.php    # Registers custom guards
    └── AppServiceProvider.php

resources/
├── css/app.css
├── js/
│   ├── app.js
│   ├── admin/              # Admin UI modules (sidebar, navigation, toasts, confirm modal)
│   ├── client/             # Client-facing scripts
│   └── shared/             # Shared frontend helpers
│   └── bootstrap.js
└── views/
    ├── components/          # Blade components
    │   ├── admin/           # Admin-only components
    │   ├── client/          # Client-only components
    │   ├── shared/          # Shared components
    │   └── layouts/         # app, admin, client layouts
    ├── admin/               # Admin portal views
    ├── client/              # Client portal views
    ├── auth/                # Login/register pages
    └── public/landing.blade.php

routes/
├── web.php                  # Session-based routes
├── api.php                  # JWT-based API routes
└── console.php

database/
├── migrations/
├── factories/
└── seeders/DatabaseSeeder.php

tests/
├── Feature/                 # Integration tests
│   ├── AuthApiTest.php
│   ├── AdminApiTest.php
│   ├── ClientApiTest.php
│   ├── AdminPortalUiTest.php
│   ├── ClientPortalDeckFlowTest.php
│   └── WebPortalTest.php
├── Unit/
│   └── Services/StudySchedulerTest.php
└── TestCase.php             # Base class with apiTokenFor() helper
```

---

## Build & Development Commands

### Setup (First Time)
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
mkdir -p database && touch database/database.sqlite
composer install
npm install
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

### Development (All Services)
```bash
composer dev    # Runs: php artisan serve + queue:listen + pail + npm run dev
```

### Individual Services
```bash
# Laravel
php artisan serve              # Development server
php artisan queue:listen       # Queue worker
php artisan pail               # Real-time log viewer

# Frontend
npm run dev                    # Vite dev server (HMR on 127.0.0.1:5173)
npm run build                  # Production build

# Database
php artisan migrate            # Run migrations
php artisan migrate:fresh      # Fresh migration
php artisan migrate:fresh --seed   # Fresh + seed demo data
php artisan db:seed            # Run seeders

# Testing
php artisan test               # Run all tests
vendor/bin/phpunit             # Run PHPUnit directly
vendor/bin/phpunit tests/Unit/Services/StudySchedulerTest.php  # Single test

# Code Quality
./vendor/bin/pint              # Laravel Pint (PHP CS Fixer)

# Cache Management
php artisan optimize:clear     # Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Architecture Deep Dive

### 1. Custom Authentication System

The app uses **three separate authentication guards**:

| Guard | Driver | Purpose | Cookie Name |
|-------|--------|---------|-------------|
| `admin` | `admin_session` | Web admin portal | `flashcard_learning_hub_admin_session` |
| `client` | `client_session` | Web client portal | `flashcard_learning_hub_client_session` |
| `api` | `jwt` | API routes | N/A (Bearer token) |

**Key Classes:**
- `App\Auth\AdminSessionGuard`: Extends Laravel's SessionGuard, validates `user->isAdmin()`
- `App\Auth\ClientSessionGuard`: Extends Laravel's SessionGuard, validates `user->isClient()`
- `App\Http\Middleware\SetSessionCookie`: Sets guard-specific session cookie names based on route
- `App\Providers\AuthServiceProvider`: Registers custom guard drivers

**Why separate session guards?**
Allows simultaneous login as admin and client in the same browser (different cookies).

### 2. User Roles & Model

```php
User::ROLE_ADMIN = 'admin'    // Full system access
User::ROLE_CLIENT = 'client'  // Learning portal access only
```

**Gamification fields:**
- `experience_points` → `level()` method calculates level (250 XP per level)
- `daily_streak` → incremented when studying on consecutive days
- `last_studied_at` → timestamp for streak calculation

### 3. Route Organization

**Web Routes (`routes/web.php`):**
| Prefix | Middleware | Controller |
|--------|------------|------------|
| `/` | public | landing page |
| `/login/client`, `/register` | `guest:client` | AuthPageController |
| `/login/admin` | `guest:admin` | AuthPageController |
| `/client/*` | `auth:client` | ClientPortalController |
| `/admin/*` | `auth:admin` | AdminDashboardController |

**API Routes (`routes/api.php`):**
| Prefix | Middleware | Purpose |
|--------|------------|---------|
| `/api/auth/*` | public (except logout) | JWT auth endpoints |
| `/api/client/*` | `auth:api` + `role:client` | Client API |
| `/api/admin/*` | `auth:api` + `role:admin` | Admin API |

### 4. Study Scheduler (SRS Algorithm)

Located in `App\Services\StudyScheduler`:

**Review intervals:**
- `again` → 15 minutes
- `hard` → 1 day
- `new` → 1 day
- `learning` → 1-7 days (exponential based on correct streak)
- `mastered` → 3-30 days (scaled by streak)
- `easy` bonus → +3 days (max 45 days)

**XP Rewards:**
- `easy` → 25 XP
- `mastered` → 20 XP
- `learning` → 12 XP
- default → 8 XP

### 5. Deck Access Control

`App\Support\DeckAccess` centralizes authorization logic:
- `accessibleQuery()` → decks user can view (owned + public active)
- `canAccess()` → check if user can view specific deck
- `canManage()` → check ownership for edits
- `canCloneOrReview()` → check if public for copying/reviewing

### 6. Request Validation

All validation uses Form Request classes:
- `AdminDeckRequest`, `ClientDeckRequest`
- `AdminFlashcardRequest`, `ClientFlashcardRequest`
- `UpdateProfileRequest`
- `RecordStudyProgressRequest`

Shared trait `NormalizesTags` converts comma-separated strings to arrays.

---

## Testing Strategy

### Test Configuration (`phpunit.xml`)
- Environment: `testing`
- Database: SQLite in-memory (`:memory:`)
- JWT secret: Pre-configured for tests

### Test Organization
- `Feature/*` → HTTP endpoint tests (controllers, routes)
- `Unit/Services/*` → Pure business logic tests

### Common Test Patterns
```php
// Get JWT token for user
$token = $this->apiTokenFor($user);

// Authenticated API request
$this->withHeader('Authorization', 'Bearer '.$token)
    ->getJson('/api/client/decks');

// Session-based auth (web routes)
$this->actingAs($user, 'client')
    ->get('/client/dashboard');
```

### Demo Data (Seeder)
After seeding (`php artisan db:seed`):
- Admin: `admin@example.com` / `password`
- Client: `client@example.com` / `password`
- 3 public decks with flashcards
- 1 private deck for client
- Sample study progress and review

---

## Environment Files

| File | Purpose |
|------|---------|
| `.env.example` | SQLite baseline for local development |
| `.env.dev` | MySQL template for development |
| `.env.staging` | MySQL template for staging |

**Key environment variables:**
```env
DB_CONNECTION=sqlite|mysql
JWT_SECRET=                    # Required for API auth
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

---

## Code Style Guidelines

1. **PHP**: Follow Laravel Pint (PSR-12 based)
   ```bash
   ./vendor/bin/pint
   ```

2. **Naming Conventions:**
   - Controllers: PascalCase, suffix with `Controller`
   - Services: PascalCase, suffix with `Service` or descriptive (e.g., `StudyScheduler`)
   - Middleware: PascalCase, suffix with `Middleware` (except built-in Laravel style)
   - Models: PascalCase, singular
   - Database tables: snake_case, plural

3. **Type Declarations:**
   - Use return type hints on all methods
   - Use property type declarations in models
   - Leverage PHP 8.2+ features

4. **Authorization:**
   - Use Gate abilities for complex authorization
   - Use `DeckAccess` support class for deck-specific checks
   - Middleware aliases: `role`, `set_session_cookie`, `guest`

---

## Security Considerations

1. **Authentication:**
   - Never share session cookies between admin/client
   - JWT tokens have expiration (configured in `config/jwt.php`)
   - Passwords always hashed (Laravel default)

2. **Authorization:**
   - `EnsureUserRole` middleware validates role on every protected route
   - `DeckAccess` prevents unauthorized deck access
   - Always check ownership before modifying decks/flashcards

3. **Input Validation:**
   - All inputs validated via Form Request classes
   - File uploads (CSV) validated for type and size

4. **SQL Injection:**
   - Use Eloquent/Query Builder (parameterized queries)
   - Never raw SQL with user input

---

## Common Development Tasks

### Adding a New API Endpoint
1. Add route to `routes/api.php` with proper middleware
2. Create controller in `app/Http/Controllers/Api/`
3. Create Form Request if validation needed
4. Add feature test in `tests/Feature/`

### Adding a New Web Route
1. Add route to `routes/web.php` with `auth:admin` or `auth:client`
2. Add controller method to appropriate Web controller
3. Create Blade view in `resources/views/`
4. Use existing components from `resources/views/components/`

### Modifying the Study Algorithm
1. Edit `app/Services/StudyScheduler.php`
2. Update `nextReviewAt()` method for interval changes
3. Update `awardStudyXp()` for XP changes
4. Run `tests/Unit/Services/StudySchedulerTest.php` to verify

### Database Changes
1. Create migration: `php artisan make:migration name`
2. Update factories if adding fields
3. Update seeders if needed
4. Run tests to ensure nothing breaks
