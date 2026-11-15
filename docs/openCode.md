# openCode.md — Building CitrusSplash with OpenCode
Practical guide for running this project inside OpenCode (the agent harness).

---

## 1. Modes

| Mode | What happens there |
|---|---|
| **Plan mode** | Discussion only. The 8 plan documents are written/edited **exclusively** in `C:\Users\Victor Lawrence\.opencode\plan`. No project files are touched |
| **Build agent** | Real work: writes code, runs/verifies, dictates terminal steps, explains. Follows `Agent.md` |

Switch agents in OpenCode's UI. Say **"start Phase N"** (or "resume") to the build agent.

## 2. Division of labor (agreed)

| Task | Who |
|---|---|
| Terminal commands | **Owner runs them** (agent dictates step-by-step, verifies output, takes over on failure) |
| Code writing | Agent writes/explains; owner does mini-task edits in VS Code |
| Reviewing | Owner reads every file in VS Code; asks anything |
| Commits/push | Owner-typed git commands (agent-guided) |

## 3. The guide documents

- **Canonical location: the project folder's `docs/` subfolder** — copied there in Phase 1 and
  versioned with the code on GitHub. Until Phase 1, working copies live in
  `C:\Users\Victor Lawrence\.opencode\plan\`. Files: `PRD.md`, `Architecture.md`,
  `Architecture-essentials.md`, `APP flow.md`, `DBD.md`, `BSD.md`, `Agent.md`, `openCode.md`
- The **repo root** carries the living learning journal: `LEARNING.md` (glossary + concept notes)
- New session? Tell the agent: *"Read the plan documents, then resume at phase N."*
- If agent behavior drifts from the plan, point it back to the docs — docs win (see Agent.md §8)

## 4. Tools the agent uses

- `read`/`grep`/`glob` — study code, verify state (safe, read-only)
- `edit`/`write` — code changes (always narrated)
- `shell` — verification and **takeover only** (the owner normally runs commands)
- Browser preview (if available) — visual checks of pages during Phases 4–7
- Question tool — decisions that belong to the owner (hosting, business values, trade-offs)

## 5. VS Code alongside OpenCode

- Owner opens the project folder in VS Code (File → Open Folder)
- Files the agent changes appear/update live; agent narrates each change
- **Rule:** don't hold unsaved edits in a file the agent is editing; if VS Code looks stale:
  `Ctrl+Shift+P` → "Revert File"
- Extensions (installed in Phase 2): PHP Intelephense · Laravel Blade · Error Lens · GitLens · DotENV
- VS Code's built-in terminal (`Ctrl+`` `) is where the owner runs dictated commands

## 6. Session checklist

**Start of a build session:**
1. Agent reads all 8 plan docs + `LEARNING.md` + `git status` (or owner reports it)
2. Confirm phase + next step in one short message
3. Work in say → show → run → explain → try → commit rhythm

**End of a session:**
1. Recap: what we built, why, what the owner did
2. `LEARNING.md` updated with every new term/concept
3. Commit + push (owner-typed)
4. Preview the next step in one sentence

## 7. Troubleshooting the workflow itself

| Symptom | Fix |
|---|---|
| Agent starts coding without explaining | Say: "slow down — explain first" (binding per Agent.md) |
| Agent drifts from the plan | Say: "re-read the plan documents" |
| Terminal step failed | Paste the **full error text** — agent diagnoses or takes over |
| VS Code shows old file contents | Ctrl+Shift+P → "Revert File" |
| Lost after a break | "Read the plan documents and tell me where we are" |
| Disagreement about direction | Check Agent.md §8 hierarchy; if still unclear → Plan mode discussion |
