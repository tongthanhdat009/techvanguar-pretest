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

### Admin Accounts
| Role | Email | Password | XP | Streak |
|------|-------|----------|-----|--------|
| Super Admin | admin@techvanguard.com | password | 5000 | 30 |
| Moderator | moderator@techvanguard.com | password | 2500 | 15 |

### Client Accounts
| Name | Email | Password | XP | Streak | Status |
|------|-------|----------|-----|--------|--------|
| John Learner | john@example.com | password | 1250 | 7 | Active |
| Sarah Student | sarah@example.com | password | 850 | 5 | Active |
| Mike Developer | mike@example.com | password | 2100 | 12 | Active |
| Emily Teacher | emily@example.com | password | 3200 | 20 | Active |
| Inactive User | inactive@example.com | password | 150 | 0 | Inactive |

## Seeded Data

After running `php artisan migrate:fresh --seed`, the database includes:

| Table | Count | Description |
|-------|-------|-------------|
| Users | 17 | 2 admins, 15 clients |
| Decks | 13 | 10 public decks across various categories |
| Flashcards | 111 | 10 cards per deck with topic-specific content |
| Study Progress | 100 | Progress records for demo clients |
| Deck Reviews | 30 | Ratings and comments for decks |

### Public Deck Categories
- **Programming**: Laravel Essentials, JavaScript ES6+, Vue.js Components, Python for Data Science
- **Languages**: Japanese Hiragana, Spanish Food Vocabulary, French Verbs
- **DevOps**: AWS Cloud Practitioner, Docker & Kubernetes
- **Databases**: MySQL Fundamentals
- **Certifications**: AWS Cloud Practitioner Prep

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
