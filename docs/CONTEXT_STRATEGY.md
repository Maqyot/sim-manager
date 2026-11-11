# Context and token strategy (source for agent-design.pdf)

## How context was provided
- Always: `AGENTS.md` (short, stable)
- Per feature: only the relevant files + a one-paragraph schema summary
- Never: whole project dumps, `vendor/`, `.env`

## Techniques used (tick the real ones)
- [ ] Task decomposition (one feature per session)
- [ ] Prompt chunking
- [ ] Context reuse via `AGENTS.md` and schema summary
- [ ] Agent specialization (smaller role prompts)
- [ ] Fresh session when the thread got long

## Reflection answers
1. How did you reduce unnecessary context?
2. How did you avoid repeating prompts?
3. How did you verify output?
4. How did you balance token cost and quality?
