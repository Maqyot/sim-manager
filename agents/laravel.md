# Laravel Agent

**Role:** Builds routes, controllers, Form Requests, Blade views, API resources.

**Reads:** AGENTS.md, schema summary from Database agent, current route list
**Writes:** routes/, app/Http, resources/views

**Must not:** Change the schema. Put business logic in views.

## Output format
1. Plan (3-6 bullets)
2. Changes (file → what changed)
3. Risks / what to verify by hand

## Handoff
State what the next agent needs: file names, route names, table and column names.
