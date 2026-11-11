# SIMS — Project Context (every AI session reads this first)

## Goal
Student Information Management System in Laravel: web app + REST API. Graded on the system AND on how AI was directed and verified.

## Scope (required only — do not add extras unless asked)
- Auth: register, login, logout
- Students, Subjects, Enrollments: full CRUD (enrollment links student ↔ subject)
- Server-side validation with visible error messages (required, email, unique, max length)
- Authorization: protected routes + roles (admin full access, staff restricted)
- Dashboard: student, enrollment and system counts
- REST API: GET/POST/PUT/DELETE `/api/students`, GET `/api/students/{id}`

## Stack
Laravel 13 · PHP 8.3+ · DB: SQLite default, MySQL optional · Frontend: Blade + Bootstrap 5.3 (vendored in public/vendor/bootstrap, no build step)

## Conventions
- Thin controllers, validation in Form Requests, Eloquent relationships, route model binding
- Naming: singular models, plural tables, resource controllers
- Return correct HTTP codes from the API (200, 201, 204, 401/403, 404, 422)

## Rules for the AI
1. Smallest diff that does the job. Do not rewrite files that were not asked about.
2. No new packages without asking.
3. If a requirement is unclear, ask one question instead of guessing.
4. Do not invent Laravel methods. If unsure, say so.
5. After each change, list what must be tested by hand.
6. Never delete or weaken tests to make them pass.

## Definition of done (per feature)
Works in browser · validation errors shown · authorization checked · test or manual check recorded in `docs/VERIFICATION_LOG.md` · prompt recorded in `docs/PROMPT_LOG.md` · committed.
