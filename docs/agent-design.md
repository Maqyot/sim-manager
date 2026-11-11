# Agent Design — SIMS
Export to `agent-design.pdf`.

## 1. Architecture
```
Student (you)
   |
Planner Agent
   |-- Database Agent      migrations, relationships, seeders
   |-- Laravel Agent       controllers, requests, routes, views
   |-- Testing Agent       feature tests, validation + security review
   |-- Documentation Agent README, API docs, reports
Reviewer Agent             reads diffs before you commit
```
Edit this to match what you actually did.

## 2. Agent roles
| Agent | Responsibility | Input context | Output |
|---|---|---|---|
| Planner | requirements, task breakdown | handbook + rubric | ordered task list |
| Database | schema | task + ERD notes | migrations, models |
| Laravel | features | schema + route list | controllers, views |
| Testing | verification | feature code | tests, bug list |
| Documentation | docs | final code | README, PDFs |

## 3. Context management
How each agent received context (single file / feature / module), what was reused between sessions (e.g. a pasted schema summary), what was deliberately left out.

## 4. Token management
- Task decomposition used
- Prompt chunking examples
- How you avoided resending the whole project
- Cost vs quality tradeoffs

## 5. Reflection questions
1. How did you reduce unnecessary context?
2. How did you avoid prompt repetition?
3. How did you verify generated output?
4. How did you balance token cost and quality?
