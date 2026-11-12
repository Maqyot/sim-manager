# Final Project Rubric Crosswalk

Reviewed 2026-11-12 against `FinalProjectHandbook.pdf`, `FinalProjectRubric.pdf`, and `AdditionalBonus.txt`.

**Review scope:** source and documentation review. Automated tests were not rerun for this checklist; use the existing test suite and manual demonstration before submission. Items marked **Complete in project** mean supporting code or files are present, not that every item has been freshly reverified.

## Core rubric (100%)

| Rubric area | Weight | Evidence in this project | Status / remaining work |
|---|---:|---|---|
| Authentication | 5% | `app/Http/Controllers/AuthController.php`; `resources/views/auth/`; `tests/Feature/AuthTest.php` | **Complete in project.** Demonstrate register, login, and logout. |
| Authorization | 5% | `app/Http/Middleware/EnsureAdmin.php`, `EnsureStaff.php`, `EnsureStudent.php`; protected groups in `routes/web.php` and `routes/api.php` | **Complete in project.** Demonstrate admin, staff, student, and guest boundaries. |
| CRUD | 10% | Student, subject, and enrollment controllers, requests, views, and `StudentWebTest.php`, `SubjectTest.php`, `EnrollmentTest.php` | **Complete in project.** Show create, view, update, delete, and a validation error for each relevant module. |
| Database design and Eloquent | 10% | `database/migrations/`; `app/Models/Student.php`, `Subject.php`, `Enrollment.php` | **Complete in project.** Explain the student–enrollment–subject relationships and unique enrollment term constraint. |
| REST API | 10% | `routes/api.php`; `app/Http/Controllers/Api/`; `tests/Feature/StudentApiTest.php`; `postman/sims.postman_collection.json` | **Complete in project.** Demonstrate all five student endpoints, token auth, validation, and response codes in Postman. |
| MVC compliance | 5% | Controllers, Form Requests, Eloquent models, and Blade views are separated under `app/` and `resources/views/` | **Complete in project.** Explain one request from route through controller/model to view or JSON response. |
| Code quality | 5% | Shared Form Requests, API Resource, middleware, feature tests | **Present.** Review and explain the validation and authorization decisions during the demo. |
| Project structure | 5% | Laravel directories, `agents/`, `docs/`, `tests/`, `postman/` | **Complete in project.** |
| Prompt engineering | 10% | `docs/PROMPT_LOG.md` contains feature prompts and iterations | **Partially evidenced.** Add screenshots from your own AI sessions and complete the iteration summary; keep model names accurate. |
| Agent design | 10% | `agents/*.md`, `docs/agent-design.md` | **Partially evidenced.** The files define roles, but the recorded work says the implementation used one primary assistant and no subagents. Describe this honestly; do not claim a multi-agent run that did not happen. |
| Context and token management | 5% | `docs/CONTEXT_STRATEGY.md`, `docs/WORKFLOW.md`, `docs/agent-design.md` | **Partially evidenced.** Fill in concrete examples from your own workflow and answer the reflection questions. |
| Validation and error handling | 5% | `app/Http/Requests/`; `tests/Feature/` | **Complete in project.** Demonstrate invalid required fields, duplicate values, and API 422 behavior. |
| AI output verification | 5% | `docs/VERIFICATION_LOG.md` | **Partially evidenced.** Entries exist, but run the checks you plan to claim for the final hand-in and record the real results. |
| Technical documentation | 5% | `README.md`, API table, Postman collection, `docs/` | **Partially evidenced.** Capture the required screenshots and ensure setup/API instructions match your final environment. |
| Reflection | 5% | `docs/reflection.md` | **Incomplete.** Answer all prompts with your own experience, including what failed, corrections, and lessons learned. |

## Required hand-in files and presentation

The handbook/rubric asks for `ai-portfolio.pdf`, `agent-design.pdf`, and `reflection.pdf`, plus screenshots and a demonstration. The repo now contains both Markdown source notes and draft exports at `docs/ai-portfolio.pdf`, `docs/agent-design.pdf`, and `docs/reflection.pdf`. Review the marked personal sections and add genuine screenshots before treating the reports as final. Do not fabricate screenshots, AI comparisons, model names, or verification results.

Before presenting, run the project locally, execute the test suite, and manually demonstrate login/logout, role restrictions, CRUD, validation messages, the dashboard, and the student API in Postman. Record the actual date and outcome in `docs/VERIFICATION_LOG.md`.

## Optional bonus opportunities

| Bonus | Evidence/status |
|---|---|
| Multi-agent workflow (+3) | Agent role prompt files exist, but current prompt records say no subagents were used. Do not claim this bonus without evidence of an actual specialized workflow. |
| AI comparison (+2) | No comparison study is recorded. Only complete this if you actually compare the requested tools/models. |
| API documentation (+1) | Postman collection exists; include it in the hand-in and demonstrate it. |
| Automated tests (+2) | Laravel feature tests exist. Run them before submission and record the real result. |
| Deployment (+2) | Wasmer deployment history and checks are recorded in `docs/PROMPT_LOG.md`; authenticated dashboard verification was not completed after the last deployment. Recheck the live demo and report accurately. |
| Professional Git practices (up to +5, separate note) | Current history has 17 commits with descriptive messages. They are dated 2026-11-12, so the history shows incremental commits but not activity spread across multiple days. Add your own Git/AI reflection; keep the existing local database change out of commits unless you intentionally want to share it. |
