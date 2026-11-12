# AI Engineering Portfolio — SIMS

Student: Marcuel L. Gubaynon

Course: Laravel Web Development

Project: Student Information Management System

Prepared: 12 November 2026

> Draft based on the project prompt and verification logs. Add your own screenshots and complete the marked personal details before submitting this report as final.

## 1. AI platforms recorded

| Platform | Model recorded | Project use evidenced in the logs | Personal selection reason |
|---|---|---|---|
| GitHub Copilot SDK in Visual Studio Code | Not recorded in the historical entries | Iterative implementation of dashboard, student, subject, enrollment, schedule, authentication, and messaging features | **Student to complete:** explain why you chose this tool and what worked well for you. |
| Codex desktop | GPT-6 in the 12 November entries | Events and sessions, Wasmer production debugging, rubric review, and workflow documentation | **Student to complete:** explain why you chose this tool and what worked well for you. |

The project log does not record a comparison run across ChatGPT, Claude, Gemini, and Copilot. No comparison claim is made here.

## 2. Representative prompt history

The quotations below are copied from the project prompt log. The refined prompts are summaries when the exact follow-up wording was not recorded.

### Dashboard and directory design

- **Initial prompt:** “can you copy some of the menu of this site? https://preskool.dreamstechnologies.com/html/index.html into my project”
- **Refinement recorded:** Use a responsive sidebar for the existing Dashboard, Students, Subjects, and Enrollments routes.
- **Decision:** Keep the project's own Bootstrap styling and real records; do not copy the reference site's assets or add unrelated menu destinations.
- **Verification recorded:** Focused auth test and feature coverage. The log says the manual responsive browser check remained to be done.

### Subject schedule

- **Initial prompt:** “for subject also can you add the time? also update the database”
- **Refinement recorded:** Store meeting weekdays and a start/end time range; require a complete schedule when any schedule field is entered.
- **Verification recorded:** Migration/status checks and feature cases for creating, invalidating, updating, and clearing a schedule.

### School events and sessions

- **Initial prompt:** Request for dashboard ideas including events, classes, and sessions.
- **Refinement/decision recorded:** Add event management and show sessions grouped from existing school-year/semester enrollment values rather than duplicating term data.
- **Verification recorded:** Migration applied; 50 tests and 267 assertions passed according to the project log. Manual browser review remained outstanding.

### Wasmer dashboard production error

- **Debug evidence:** The project log records `SQLSTATE[HY000]: General error: 1 no such table: events` from Wasmer stderr.
- **User refinement:** The user provided the admin login to reproduce the signed-in failure.
- **Correction recorded:** Guard dashboard event queries when the table is absent and configure migrations in the Wasmer build flow.
- **Verification recorded:** PHP lint passed; `/login` returned 200, `/up` returned 200, and unauthenticated `/dashboard` returned 302. Authenticated dashboard verification was not completed after deployment.

### Student registration with administrator approval

- **Initial prompt:** “for student form put a registration form, then in admin dashboard put a like a verification box that will accept for any student newly registrated before they can login”
- **Result recorded:** Added a student profile/password form, created accounts in a pending state, showed pending requests to admins, and blocked student login until approval. Existing records can register only with profile details matching the stored record.
- **Checks recorded:** PHP lint, migration, Blade compilation, and route checks succeeded. Browser and feature tests remain to be run.
## 3. Prompt iteration lessons

| First direction | Added context or constraint | Improvement |
|---|---|---|
| Copy a reference school dashboard | Keep existing SIMS routes, permissions, and data; use original Bootstrap styling | Avoided adding reference-only features or copied assets. |
| Add a student timeline like the reference | Use only enrollment and schedule data present in SIMS | Avoided inventing attendance, quiz, or parent details. |
| Fix the dashboard error | Use the exact production SQLite error and the affected authenticated dashboard | Led to a schema-aware fallback and a migration deployment change. |

## 4. AI output verification examples

| Suggestion or issue | Risk found | Correction and evidence recorded |
|---|---|---|
| Reuse a broad reference-site menu | Some destinations did not exist in SIMS | Limited navigation to existing routes; tests and route checks were recorded. |
| Use a generic `students.index` route name | Web and API route names could collide | API route names were prefixed; URL and sidebar behavior were tested. |
| Copy a student timeline with attendance and quiz activity | SIMS did not store those facts | Built the timeline from enrollment data and schedule data; feature coverage was added. |
| Query events unconditionally in the dashboard | The deployed SQLite database lacked the events table | Added schema guards and a migration build hook; authenticated browser verification remains outstanding. |

| Let newly registered student accounts sign in immediately | New portal accounts must be approved by an administrator first | Added a pending state, admin-only approval action, and login block. PHP lint, migration, Blade compilation, and route checks passed; browser and feature tests remain outstanding. |

See `docs/VERIFICATION_LOG.md` for the longer history and exact recorded checks. Historical results are not a substitute for rerunning checks before the final demonstration.

## 5. Screenshots and optional AI comparison

**Screenshots to add from your own sessions:**

1. An AI prompt and its refined version in the tool you actually used.
2. Login and dashboard.
3. Students with a visible validation error.
4. Enrollments.
5. A successful Postman student API request.

No screenshots were present in the project logs when this draft was prepared. Capture genuine screens and add them before final submission. Do not use mockups as evidence.

**Optional comparison study:** Not recorded. If you perform it, use the same task prompt with the required tools, save each result, compare correctness and Laravel fit, and explain which output you chose. Otherwise leave this bonus unclaimed.
