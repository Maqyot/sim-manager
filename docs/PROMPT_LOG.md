# Prompt log (source for ai-portfolio.pdf)

Record real prompts only. One entry per feature. Add a screenshot filename for each.

## Entry template
- **Date / tool / model:**
- **Feature:**
- **Agent role used:**
- **Initial prompt:**
- **What was wrong with the result:**
- **Refined prompt:** (what context or constraint you added)
- **Validation prompt:** (asked AI to review its own output)
- **Debug prompt:** (include the actual error text)
- **Result:**
- **Screenshot:**

## Iteration summary
| Feature | v1 problem | v2 change | Improvement |
|---|---|---|---|
| | | | |

## Feature entries

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Responsive SIMS sidebar navigation
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "can you copy some of the menu of this site? https://preskool.dreamstechnologies.com/html/index.html into my project"
- **What was wrong with the result:** Not applicable; this is the initial implementation.
- **Refined prompt:** Selected "Add a sidebar for the existing Dashboard, Students, Subjects, and Enrollments" after clarification.
- **Validation prompt:** No separate validation prompt; added and ran a focused feature test.
- **Debug prompt:** None.
- **Result:** Added a responsive sidebar for existing SIMS routes using the project's vendored Bootstrap; retained logout and guest authentication pages.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Dashboard and student-directory visual refresh
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "in my /student can you copy the design for this https://preskool.dreamstechnologies.com/html/student-dashboard.html and for my /dashboard also copy this design https://preskool.dreamstechnologies.com/html/index.html"
- **What was wrong with the result:** Not applicable; this is the initial implementation.
- **Refined prompt:** Confirmed the existing `/students` directory is the student page target. Used an original Bootstrap design inspired by general dashboard patterns, without reproducing proprietary styling or assets.
- **Validation prompt:** No separate validation prompt; added feature coverage and ran the full test suite.
- **Debug prompt:** None.
- **Result:** Redesigned `/dashboard` and `/students` while keeping the app's existing data, route permissions, search, pagination, and CRUD actions.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Subject and enrollment directory visual refresh
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "also in subject and enrollments"
- **What was wrong with the result:** Not applicable; this is the initial implementation.
- **Refined prompt:** Applied the preceding request's dashboard-inspired style to the existing Subject and Enrollment listings, preserving role-gated actions and paging.
- **Validation prompt:** No separate validation prompt; added feature tests for displayed records and permissions, then ran the test suite.
- **Debug prompt:** None.
- **Result:** Restyled both directory pages with summary panels and polished tables using existing records only.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Subject meeting schedule fields
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "for subject also can you add the time? also update the database"
- **What was wrong with the result:** Not applicable; this is the initial implementation.
- **Refined prompt:** Confirmed the subject schedule should contain multiple meeting weekdays and one start/end time range.
- **Validation prompt:** No separate validation prompt; added create, invalid-schedule, update, and clear-schedule feature tests.
- **Debug prompt:** None.
- **Result:** Added nullable weekday and time columns with a migration, form validation, and schedule display in the subject catalogue. Applied the migration to the configured SQLite database.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Change-password navigation and workflow
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "in my dasboard also, can you add the top menu beside of logout the change password?"
- **What was wrong with the result:** Not applicable; this is the initial implementation.
- **Refined prompt:** Added a protected password-change page and workflow that verifies the current password before saving.
- **Validation prompt:** No separate validation prompt; added tests for the menu, successful update, and invalid credentials.
- **Debug prompt:** None.
- **Result:** Added a top-bar link beside Logout and an authenticated, validated password-change form.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Student number and temporary-course-password login
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "for student is this student number = username and course = password"
- **What was wrong with the result:** Not applicable; this is the initial implementation.
- **Refined prompt:** Initially selected a required password change, then requested that students go directly to the dashboard. Removed the forced password-change step.
- **Validation prompt:** No separate validation prompt; updated tests for direct first login and role boundaries.
- **Debug prompt:** None.
- **Result:** Students provision their linked account with student number and course on first login and go directly to the portal. Existing staff login remains email-based.
- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Direct student dashboard after first login
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "WHEN I LOGIN AS STUDENT WHY THIS SHOWS For your security, choose a private password to replace your temporary course password. IT SHOULD NOT"
- **What was wrong with the result:** The forced password-change flow blocked student dashboard access despite the user not wanting to see it.
- **Refined prompt:** Confirmed the flow should be removed and students should go directly to their dashboard.
- **Validation prompt:** No separate validation prompt; added a regression assertion for direct dashboard access.
- **Debug prompt:** None.
- **Result:** Removed forced password changes and their database flag; voluntary password updates still verify the current password.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Role-specific login help and student menu
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "in my login when i select student this must not shown Staff account? Register First student login? Use your student number and course as your temporary password. if student login then hide the change password in menu when i also register as staff it messages appear. contact the system admin, before asking your credentials"
- **What was wrong with the result:** Staff guidance and first-login text were shown regardless of selected login type, and students saw the Change Password link.
- **Refined prompt:** Selected replacing the staff registration link with a message to contact the system admin for credentials.
- **Validation prompt:** No separate validation prompt; added feature assertions and checked the login selector in the browser.
- **Debug prompt:** None.
- **Result:** Removed the registration/temporary-password help text, shows the admin-contact message only for staff login, and hides Change Password from student navigation.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Admin student timeline and upcoming subject
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "in admin panel when i click a student it will be shown of a timeline that same as this in https://preskool.dreamstechnologies.com/html/parent-dashboard.html emmas timeline and whats coming up for the next subject"
- **What was wrong with the result:** Not applicable; this is the initial implementation.
- **Refined prompt:** Used actual enrollment history for the timeline and existing weekday/time subject schedules to calculate the next enrolled subject; no simulated attendance or activity.
- **Validation prompt:** No separate validation prompt; added feature coverage for timeline, next scheduled subject, and student data isolation.
- **Debug prompt:** None.
- **Result:** Updated the admin student detail page with enrollment timeline, upcoming class card, academic summary, and full enrollment table.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Student profile details page refresh
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "also can you update my student that same as this? https://preskool.dreamstechnologies.com/html/student-details.html"
- **What was wrong with the result:** Not applicable; this is the initial implementation.
- **Refined prompt:** Adapted the student 360/profile layout to SIMS fields only; omitted unavailable parent, attendance, address, and other template-only data.
- **Validation prompt:** No separate validation prompt; expanded the profile feature test and ran the full suite.
- **Debug prompt:** None.
- **Result:** Added profile overview cards, personal information, student portal status, next subject, timeline, enrollment highlights, and complete enrollment table.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Private student/admin chat
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "CAN YOU ADD ALSO A CHATBOX, IF STUDENT SENT, ONLY FOR ADMIN CAN VIEW, SAME AS ADMIN"
- **What was wrong with the result:** Not applicable; this is the initial implementation.
- **Refined prompt:** Confirmed students should see their own conversation, admins can see all conversations and reply, and other staff should not access the admin inbox.
- **Validation prompt:** No separate validation prompt; added tests for message sending, replies, privacy, validation, and role access.
- **Debug prompt:** None.
- **Result:** Added a private, database-backed student/admin messaging inbox using existing Laravel and Bootstrap, with no external packages.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-12 / Copilot SDK in VS Code / model not recorded
- **Feature:** Docker packaging
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "can you make me a dockerfile on this"
- **What was wrong with the result:** Not applicable; initial implementation.
- **Refined prompt:** None.
- **Validation prompt:** None.
- **Debug prompt:** None.
- **Result:** Added a PHP 8.3 Apache Dockerfile, build-context exclusions, and README instructions for configuring the app key, persisting SQLite data, migrating, and running the container.
- **Screenshot:** Not captured.
- **Screenshot:** Not captured.

- **Date / tool / model:** 2026-11-11 / Copilot SDK in VS Code / model not recorded
- **Feature:** Student login and student portal
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "in my login, it has also have 2 selection admin login or student login. if student login then the menu looks like this https://preskool.dreamstechnologies.com/html/student-dashboard.html"
- **What was wrong with the result:** Not applicable; this is the initial implementation.
- **Refined prompt:** Confirmed student accounts should be separate login accounts linked to existing student records.
- **Validation prompt:** No separate validation prompt; added tests for first login, required password change, student-only data, and staff/API access restrictions.
- **Debug prompt:** None.
- **Result:** Added staff/admin versus student login choices, student-account support, scoped student dashboard/enrollment pages, and role-based navigation. Applied the student-link migration to the configured SQLite database. Student screens use existing SIMS data rather than copying template assets.
- **Screenshot:** Not captured.

## Feature entry: Events and academic sessions

- **Date / tool / model:** 2026-11-12 / Codex desktop / GPT-6
- **Feature:** School events and academic session overview
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "im a student, our trainor just give me a list brief to make our student system, the instructions is on my desktop folder name project, while my project is on my visual code and its open now, and its call sims, can you check and enhance it if theres anything it can enhance. also i want the idea of dashboard on this site https://preskool.dreamstechnologies.com/html/, the dashboard, student and event the classes and sesion, i will authorize you to edit and enhance my sims"
- **Follow-up:** "go ahead"
- **Result:** Added a role-aware event calendar and admin event editing, upcoming event panels on admin and student dashboards, and a session overview based on existing school-year/semester enrollment values. Renamed the subject navigation label to Classes & subjects.
- **Validation:** Applied the events migration to local SQLite; php artisan test passed (50 tests, 267 assertions). Manual browser review remains to be done.
- **Screenshot:** Not captured.
- **Date / tool / model:** 2026-11-12 / Codex desktop / GPT-6
- **Feature:** Production dashboard resilience when the events migration is missing
- **Agent role used:** Primary assistant; no subagent
- **Initial prompt:** "please fix it"
- **What was wrong with the result:** The Wasmer SQLite package did not contain the `events` table; the authenticated dashboard failed on its upcoming-events query.
- **Refined prompt:** User provided admin login to reproduce the signed-in failure.
- **Validation prompt:** None.
- **Debug prompt:** `SQLSTATE[HY000]: General error: 1 no such table: events` from Wasmer stderr.
- **Result:** Added a schema check to admin and student dashboard event queries, returning an empty event list when the table is absent; configured the Wasmer Composer build hook to run migrations with `--force`.
- **Screenshot:** Not captured; Wasmer deployment v10 is active. Authenticated browser verification was interrupted by the computer-use safety policy.
