# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 11 flashcard/study application with a separate admin portal and client portal. It uses custom session guards to allow simultaneous admin and client logins with separate session cookies, and JWT authentication for API routes.

## Development Commands

### Laravel Development
```bash
# Start the development server (PHP, Vite, Queue, Logs)
composer dev

# Individual services
D:\xampp\php\php.exe artisan serve              # Start Laravel server
D:\xampp\php\php.exe artisan queue:listen       # Start queue worker
D:\xampp\php\php.exe artisan pail               # Start log watcher

# Database
D:\xampp\php\php.exe artisan migrate            # Run migrations
D:\xampp\php\php.exe artisan migrate:fresh      # Fresh migration with seeding
D:\xampp\php\php.exe artisan db:seed            # Seed the database

# Testing
D:\xampp\php\php.exe artisan test               # Run all tests
D:\xampp\php\php.exe artisan test --filter      # Run specific test filter
vendor/bin/phpunit             # Run PHPUnit directly
vendor/bin/phpunit tests/Unit/Services/StudySchedulerTest.php  # Run single test file

# Code Quality
./vendor/bin/pint              # Laravel Pint code formatter

# Clear caches (run after modifying config, routes, or views)
D:\xampp\php\php.exe artisan config:clear        # Clear configuration cache
D:\xampp\php\php.exe artisan route:clear         # Clear route cache
D:\xampp\php\php.exe artisan cache:clear         # Clear application cache
D:\xampp\php\php.exe artisan view:clear          # Clear compiled views
# All-in-one clear:
D:\xampp\php\php.exe artisan optimize:clear      # Clear all caches (config, routes, views, cache)
```

### Frontend Development
```bash
npm run dev                    # Start Vite dev server
npm run build                  # Build for production
```

## Architecture

### Custom Authentication Guards

The app implements custom session guards (`admin_session`, `client_session`) that extend Laravel's `SessionGuard` to validate user roles. Each guard uses a separate session cookie (`flashcard_learning_hub_admin_session`, `flashcard_learning_hub_client_session`) via the `SetSessionCookie` middleware.

- **`App\Auth\AdminSessionGuard`**: Validates users have `role === 'admin'`
- **`App\Auth\ClientSessionGuard`**: Validates users have `role === 'client'`
- **`App\Http\Middleware\SetSessionCookie`**: Sets guard-specific session cookie names
- **`App\Providers\AuthServiceProvider`**: Registers custom guards

Guards are configured in [config/auth.php](config/auth.php) and registered in [bootstrap/providers.php](bootstrap/providers.php).

### User Model

The [User](app/Models/User.php) model has two roles:
- `ROLE_ADMIN` (`admin`) - Full access to admin portal and API
- `ROLE_CLIENT` (`client`) - Access to client portal, deck management, study features

Includes gamification: `experience_points`, `daily_streak`, `last_studied_at`, and a `level()` method.

### Route Organization

- **Web routes** ([routes/web.php](routes/web.php)):
  - Public landing, login/register pages
  - Client portal routes under `/client/*` with `auth:client` middleware
  - Admin portal routes under `/admin/*` with `auth:admin` middleware

- **API routes** ([routes/api.php](routes/api.php)):
  - Auth endpoints (register, login, logout, me) using JWT
  - Client API under `/api/client/*` with `auth:api` + `role:client`
  - Admin API under `/api/admin/*` with `auth:api` + `role:admin`

### Services Layer

Key business logic is encapsulated in services under [app/Services/](app/Services/):

- **`StudyScheduler`**: Spaced repetition algorithm, XP rewards, daily streak tracking, next review date calculation
- **`CsvImportService`**: Imports decks/flashcards from CSV
- **`CsvExportService`**: Exports decks to CSV format
- **`StudySessionService`**: Manages active study sessions
- **`DeckCopyService`**: Handles copying public decks

### Form Request Validation

Validation rules are centralized in Form Requests under [app/Http/Requests/](app/Http/Requests/):

- `AdminDeckRequest`, `ClientDeckRequest`, `AdminFlashcardRequest`, `ClientFlashcardRequest`
- `UpdateProfileRequest`, `RecordStudyProgressRequest`
- Shared trait: `Concerns\NormalizesTags` - normalizes tag arrays

### View Components

Blade components are organized by section under [resources/views/components/](resources/views/components/):

- **Layouts**: `layouts/admin.blade.php`, `layouts/client.blade.php`, `layouts/auth.blade.php`, `layouts/public.blade.php`
- **Admin Components**: `admin/admin-sidebar.blade.php`, `admin/admin-breadcrumb.blade.php`, `admin/admin-toast.blade.php`, `admin/admin-confirm-modal.blade.php`, `admin/admin-table.blade.php`
- **Client Components**: `client/client-header.blade.php`, `client/progress-summary.blade.php`, `client/deck-grid.blade.php`, `client/study-section.blade.php`
- **Auth Components**: `auth/auth-logo.blade.php`, `auth/auth-alert.blade.php`, `auth/auth-form.blade.php`, `auth/auth-links.blade.php`
- **Public Components**: `public/public-navbar.blade.php`, `public/public-hero.blade.php`, `public/public-features.blade.php`, `public/public-demo.blade.php`, `public/public-footer.blade.php`, `public/public-final-cta.blade.php`
- **Common Components**: `common/empty-state.blade.php`

### Models & Relationships

- **User** → hasMany → Deck, StudyProgress, DeckReview
- **Deck** → belongsTo → User (owner), hasMany → Flashcard, DeckReview
- **Flashcard** → belongsTo → Deck, hasOne → StudyProgress (per user)
- **DeckReview** → belongsTo → User, Deck
- **StudyProgress** → belongsTo → User, Flashcard

Decks have visibility (`private`/`public`), tags (array cast), and can reference a `source_deck_id` for copied decks.

### Frontend Stack

- **Vite** + **Laravel Vite Plugin** for asset compilation
- **Tailwind CSS** for styling
- **JavaScript Modules**: Organized by section (admin, client, auth, public) for maintainability

### Frontend Directory Structure

```
resources/
├── css/
│   ├── admin/admin.css      # Admin-specific styles
│   ├── client/client.css    # Client portal styles
│   ├── auth/auth.css        # Authentication page styles
│   └── public/public.css    # Landing page styles
├── js/
│   ├── admin/admin.js       # Admin interactions (sidebar, toasts, modals)
│   ├── client/client.js     # Client portal interactions
│   ├── auth/auth.js         # Form validation, password toggle
│   └── public/public.js     # Landing page interactions (flip cards, smooth scroll)
└── views/
    ├── layouts/             # Section-specific layouts
    └── components/          # Organized by section (admin, client, auth, public, common)
```

### Adding New Pages

When creating new pages, follow these conventions:

1. **Use appropriate layout**: Admin pages use `layouts.admin`, Client pages use `layouts.client`, Auth pages use `layouts.auth`
2. **Page icon**: Use the icon from [public/assets/icon-logo.svg](public/assets/icon-logo.svg) for branding consistency
3. **Component organization**: Place reusable components in the appropriate section folder under `resources/views/components/`
4. **CSS/JS**: Add section-specific styles to `resources/css/{section}/` and scripts to `resources/js/{section}/`

## Testing

- Tests use PHPUnit with SQLite in-memory database
- JWT secret is pre-configured for testing
- Tests are organized in `tests/Feature/` and `tests/Unit/`
- Run individual tests: `vendor/bin/phpunit tests/Unit/Services/StudySchedulerTest.php`
