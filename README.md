# SIMS — Student Information Management System

Laravel 13 web application with a REST API: authentication, role-based authorization, student / subject / enrollment CRUD, dashboard statistics, server-side validation and a token-protected student API.

## Requirements
PHP 8.3+, Composer, SQLite (default) or MySQL 8. Works on Laragon, XAMPP or `php artisan serve`.

## Setup
```bash
composer setup          # install, .env, key, SQLite file, migrate --seed
php artisan serve
```
Manual equivalent:
```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite      # Windows: type nul > database\database.sqlite
php artisan migrate --seed
php artisan serve
```
Open http://127.0.0.1:8000

| Role  | Email           | Password |
|-------|-----------------|----------|
| Admin | admin@sims.test | password |
| Staff | staff@sims.test | password |

Change these before using real data. Self-registration always creates `staff`.

## Docker
The Docker image runs PHP 8.3 with Apache and SQLite. Docker Engine must be installed and running.

1. Copy `.env.example` to `.env`. Set `APP_KEY` to the output of `php artisan key:generate --show` and set `APP_URL=http://localhost:8080`.
2. Build the image:
   ```bash
   docker build -t sims .
   ```
3. Apply database migrations to the persistent SQLite volume:
   ```bash
   docker run --rm --env-file .env -e DB_CONNECTION=sqlite -e DB_DATABASE=/var/lib/sims/database.sqlite -v sims-data:/var/lib/sims sims php artisan migrate --force
   ```
4. Start the application:
   ```bash
   docker run --name sims --env-file .env -e DB_CONNECTION=sqlite -e DB_DATABASE=/var/lib/sims/database.sqlite -p 8080:80 -v sims-data:/var/lib/sims sims
   ```
   Open http://localhost:8080. The named `sims-data` volume preserves the SQLite database across container restarts. Stop the container with `docker stop sims`.

## Database
**SQLite (default):** nothing to configure.

**MySQL (Laragon):** create an empty database `sims` (utf8mb4), then in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sims
DB_USERNAME=root
DB_PASSWORD=
```
and run `php artisan migrate --seed`.

`database/sims_export.sql` is a MySQL export of the schema and seed data (includes the `migrations` table, so `php artisan migrate` after importing does nothing). Migrations are the source of truth.

| Table | Purpose | Key constraints |
|---|---|---|
| users | accounts, `role` (admin/staff), hashed `api_token` | unique email |
| students | student records | unique student_number and email |
| subjects | subject catalog | unique code |
| enrollments | student ↔ subject per term | FKs cascade on delete; unique (student, subject, school_year, semester) |

## Roles and access
| Feature | Staff | Admin |
|---|---|---|
| Dashboard, view lists | yes | yes |
| Add / edit students | yes | yes |
| Delete students | no | yes |
| View subjects | yes | yes |
| Add / edit / delete subjects | no | yes |
| Enroll / update enrollment | yes | yes |
| Remove enrollment | no | yes |

All pages require login. Restricted routes use the `admin` middleware (`EnsureAdmin`) and return 403.

## Frontend
Bootstrap 5.3.8 is vendored in `public/vendor/bootstrap/` and loaded in `resources/views/layouts/app.blade.php` with `asset()`, so the app works offline and needs no Node build. It uses the navbar, cards, responsive tables, form validation states (`is-invalid` + `invalid-feedback`), badges and pagination components, and switches between light and dark themes with the visitor's system setting. To upgrade Bootstrap, replace the two files in that folder.

## Validation
Shared Form Requests (`StudentRequest`, `SubjectRequest`, `EnrollmentRequest`) are used by both the web forms and the API: required fields, email format, unique entries (ignoring the record being edited), maximum lengths, numeric ranges, `YYYY-YYYY` school year, one enrollment per student + subject + term.

## REST API
Base `/api`. Send `Accept: application/json`. Authentication is a bearer token issued by `POST /api/login` (one active token per user; logging in again replaces it, logout revokes it). Only the SHA-256 hash is stored.

```bash
curl -X POST http://127.0.0.1:8000/api/login -H "Accept: application/json" \
     -d "email=admin@sims.test" -d "password=password"

curl http://127.0.0.1:8000/api/students -H "Accept: application/json" \
     -H "Authorization: Bearer <token>"
```

| Method | Endpoint | Result |
|---|---|---|
| POST | /api/login | 200 token · 422 bad credentials · 429 throttled |
| POST | /api/logout | 200, token revoked |
| GET | /api/user | current user |
| GET | /api/students | 200 paginated, `?q=` search |
| GET | /api/students/{id} | 200 · 404 |
| POST | /api/students | 201 · 422 |
| PUT | /api/students/{id} | 200 · 404 · 422 |
| DELETE | /api/students/{id} | 204 admin only · 403 staff |
| any | without valid token | 401 |

Body: `student_number`, `first_name`, `last_name`, `email`, `course`, `year_level` (1-6). A Postman collection is in `postman/` (set the `token` variable after login).

## Tests
```bash
php artisan test
```
Feature tests cover authentication, authorization, validation, CRUD, enrollment uniqueness, cascade deletes and the API. They use in-memory SQLite (needs the `pdo_sqlite` PHP extension).

## Architecture
```
app/Http/Controllers      thin controllers (web) + Api/ (JSON)
app/Http/Requests         validation rules shared by web and API
app/Http/Middleware       EnsureAdmin, AuthenticateApiToken
app/Http/Resources        StudentResource (API shape)
app/Models                Eloquent models and relationships
resources/views           Blade views styled with Bootstrap 5 (layout, partials, one folder per module)
public/vendor/bootstrap   Bootstrap 5.3.8 CSS + JS bundle, served locally (no CDN, no npm build)
database/                 migrations, factories, seeder, SQL export
tests/Feature             feature tests
```

## Assignment rubric review

See [the rubric crosswalk](docs/RUBRIC_CHECKLIST.md) for requirement evidence, submission gaps, and final demonstration reminders. It distinguishes implemented code from work that still needs your own screenshots, reflection, or verification.

## AI-assisted development files
`AGENTS.md`, `agents/` and `docs/` hold the context file, agent role prompts, workflow, prompt log, verification log, context strategy and decision notes. Draft report PDFs and their editable Markdown sources are in `docs/ai-portfolio.pdf`, `docs/agent-design.pdf`, and `docs/reflection.pdf`.

## Screenshots
Add screenshots of login, dashboard, students (with a validation error), enrollments and a Postman request here.
