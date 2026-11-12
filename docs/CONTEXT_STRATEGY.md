# Context and Token Strategy — SIMS

This document is a repeatable strategy for AI-assisted work on SIMS. It is a guide, not a claim that every technique was used on every historical feature.

## Context to include

- Always include the short, stable project rules in `AGENTS.md`.
- Include the one handbook/rubric requirement being addressed.
- For a feature, provide only its related routes, controller, Form Request, model, view, migration, and existing tests.
- For API work, include the auth middleware and API resource/status-code expectations.
- Pass a short schema and route summary to the next role instead of resending files already summarized.
- For debugging, include the exact error, the action/route, and the environment where it occurred.

## Context to leave out

Do not paste `.env`, passwords, tokens, `vendor/`, compiled/cache files, the full database, or unrelated feature folders. Do not ask for a complete rewrite when a small, targeted patch is enough.

## Techniques and how to use them

- **Task decomposition:** split a request into one feature and acceptance checks.
- **Prompt chunking:** separate schema, implementation, review, and documentation when a feature is broad.
- **Context reuse:** use `AGENTS.md` for stable rules and pass short handoffs for schema/routes.
- **Role specialization:** use the role prompts in `agents/` for focused responsibilities; record separately whether they ran as actual agents or were used as a checklist.
- **Incremental verification:** verify a change before stacking another change on top of it.
- **Fresh context:** start a concise handoff summary when a thread becomes too long; keep source-of-truth details in project files.

## Reflection guidance

Answer these using real examples from `docs/PROMPT_LOG.md` and `docs/VERIFICATION_LOG.md`; replace or expand the prompts below with your own experience before exporting the report.

1. **How did you reduce unnecessary context?** Describe one feature and name the files or summary provided. If you do not remember, say so rather than guessing.
2. **How did you avoid prompt repetition?** Identify a stable instruction or handoff you reused, or note that this was not consistently done.
3. **How did you verify generated output?** Give the exact command/browser/Postman check and its recorded result. Distinguish tests from checks that remain pending.
4. **How did you balance token cost and quality?** Explain which context you kept because it affected schema, security, or compatibility and what you excluded as unrelated.

## Evidence note

The repository has project/role instructions and a feature-by-feature prompt log. Historical entries commonly say “Primary assistant; no subagent.” That evidence supports AI-assisted iterative development, but by itself does not prove a multi-agent workflow was executed. Keep the report consistent with the actual session records.
