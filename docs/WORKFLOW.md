# AI-Assisted Development Workflow — SIMS

This workflow is designed for changes to this Laravel Student Information Management System. Use it one feature at a time. The role files in `agents/` are specialized instructions that can be given to separate agents or used as review checklists by one assistant.

## Important: design versus project history

The project history in `docs/PROMPT_LOG.md` records work by a primary assistant and says no subagents were used. The workflow below is the team's repeatable process for future work and for explaining the design. Do not describe a role prompt as an agent that actually ran. For the assignment, record which roles were actually invoked for each feature in the prompt log.

## Workflow

### 1. Define the task and acceptance checks — Planner

Read `AGENTS.md`, the relevant handbook/rubric requirement, and only the affected feature files. Turn the request into a small plan with observable acceptance checks. Identify roles, routes, fields, validation, authorization, and deployment constraints that could be affected. Ask the student one focused question if a required product decision is unclear.

**Deliverable:** a short task plan. Do not code yet.

### 2. Check the data model — Database role (when schema changes)

Read the current migrations and models. Describe the proposed table or column change, keys, relationships, nullability, and migration rollback. Add a new migration for an already-applied schema; do not rewrite a migration that may have run. Keep business constraints in the database where appropriate and in validation rules.

**Handoff:** migration name, table/column names, relationships, and any model changes.

### 3. Implement the feature — Laravel role

Read the handoff, current routes, relevant controller, Form Request, model, view, and nearby tests. Follow MVC: keep validation in Form Requests, use Eloquent relationships, use route model binding, and keep Blade templates focused on presentation. Protect routes with the existing role middleware. Return the expected API response and status code for API work.

**Handoff:** files changed, routes, validation behavior, role restrictions, and manual checks still needed.

### 4. Review the change — Reviewer role

Inspect the complete diff and compare it with `AGENTS.md` and the acceptance checks. Look for authorization gaps, mass assignment, missing validation, incorrect relationships, duplicate queries, accidental secrets or data files, broken route names, and unsupported claims in documentation. Report findings before fixes; the reviewer should not silently change the implementation.

### 5. Verify — Testing role and student

Add or update a feature test for each important success path and rejection path. Check guest, staff, admin, and student access as relevant. For APIs, verify authentication, JSON shape, and status codes such as 200, 201, 204, 401/403, 404, and 422. Run the relevant test suite and record the exact command, date, and result. The student manually checks the browser or Postman behavior that automated tests cannot establish.

Never remove or weaken a test to get a green result. A failed or unrun check must be recorded accurately.

### 6. Update project documentation — Documentation role

Update `README.md`, API examples, `docs/PROMPT_LOG.md`, and `docs/VERIFICATION_LOG.md` only where the change makes them stale. Record actual prompts and tools; mark screenshots as missing until captured. Never invent a model name, agent execution, screenshot, test result, or deployment check.

### 7. Review Git and hand off

Inspect `git status` and `git diff --check`. Keep local databases, `.env`, credentials, build artifacts, and unrelated changes out of the commit. The student reviews the diff, writes a meaningful commit message, and decides whether to commit/push. The task handoff summarizes the change, checks completed, checks still needed, and affected files.

## Feature prompt template

> Work on one SIMS feature: **[feature]**. Follow `AGENTS.md` and the role instructions in `agents/`. Relevant requirement: **[handbook/rubric item]**. Relevant files: **[small file list]**. Acceptance checks: **[observable outcomes]**. Preserve existing role boundaries and validation. Do not add dependencies or unrelated features. Before editing, report a short plan. After editing, list files changed, checks actually run with results, and manual checks I should perform. Do not claim checks that were not run.

## Debug prompt template

> Here is the exact error: **[paste error/log]**. It occurred while **[route/action, environment, time]**. Investigate the smallest likely cause in **[relevant files]**. Explain the evidence before changing code. Preserve the existing data and role restrictions. After the fix, report the exact checks run and anything still unverified.

## Review prompt template

> Review this diff against `AGENTS.md` and the acceptance checks. Do not edit files. Report only actionable findings, ordered by severity, with file/line and a concrete correction. Check authorization, validation, database integrity, API status codes, secrets/data files, and whether the verification log matches the checks actually run.
