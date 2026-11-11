# Workflow loop (one feature at a time)

1. **Plan** — Planner agent: one feature, acceptance checks.
2. **Build** — Database / Laravel agent: only the files for that feature.
3. **Review** — Reviewer agent on `git diff`.
4. **Test** — Testing agent + manual run in the browser / Postman.
5. **Log** — add rows to PROMPT_LOG and VERIFICATION_LOG.
6. **Commit** — `feat(students): CRUD with Form Request validation`.

Order of features: schema → auth → roles → students → subjects → enrollments → dashboard → API → tests → docs.
