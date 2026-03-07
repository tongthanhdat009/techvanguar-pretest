# Flashcard Learning Application

A Laravel 11 flashcard learning platform with:

- JWT authentication APIs for auth, client, and admin workflows
- MySQL-ready environment templates for development and staging
- Blade + Tailwind admin and client interfaces
- Flashcard flip animation and study progress tracking
- Seeded demo content for quick manual review

## Quick start

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

## Demo credentials

- Admin: `admin@example.com` / `password`
- Client: `client@example.com` / `password`

## API overview

### Auth
- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `GET /api/auth/me`

### Client
- `GET /api/client/decks`
- `GET /api/client/decks/{deck}`
- `GET /api/client/progress`
- `PUT /api/client/flashcards/{flashcard}/progress`

### Admin
- CRUD ` /api/admin/users`
- CRUD ` /api/admin/decks`
- CRUD ` /api/admin/flashcards`
- `GET /api/admin/statistics`

## Environment files

- `.env.example` - local sqlite-friendly baseline
- `.env.dev` - MySQL-backed development template
- `.env.staging` - MySQL-backed staging template
