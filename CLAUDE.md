# Claude Code Instructions For `jooservices/state-machine`

Read [AGENTS.md](./AGENTS.md) first.

When working in this repository:

- Prefer the smallest possible change that fits the existing package structure.
- Match repository-native style, not just formatter output.
- Understand which class or module owns the behavior before editing.
- Follow the repository's design principles for compatibility and public API safety.
- Stop and ask the user if requirements are unclear, conflicting, missing, or impossible based on real code.
- Do not silently infer, assume, or decide when repository truth is missing or conflicting.
- Route the task through the right skill instead of using one generic workflow for everything.
- Be explicit about change type, risk, dependency impact, and partially wired features.
- Keep tests and docs in sync with behavior changes.
- Respect the current runtime gaps documented in `docs/05-maintenance/01-risks-legacy-and-gaps.md`.
- Assume local hooks and CI will enforce linting, static analysis, tests, coverage, secret scanning, Conventional Commits, and semantic PR titles.
- Keep Pint as the formatting authority; PHPCS is structural, PHPStan stays max or strictest feasible, PHPMD covers maintainability, and suppressions require narrow scope plus a clear reason.
- Do not silently add PHPStan `ignoreErrors` or PHPMD global exclusions; fix real checker failures in code and report any suppression in final WHAT/WHY/HOW notes.
- Follow the approved Git flow from `AGENTS.md`: normal work branches from `develop`, release preparation uses `release/<version>` from `develop`, releases merge into `master`, and `master` merges back into `develop` after release or hotfix completion.
- Keep `SECURITY.md`, `CODE_OF_CONDUCT.md`, `CONTRIBUTING.md`, issue templates, and the pull request template aligned with current repository workflow when they are touched.
- Do not route vulnerability reports through public issues; follow `SECURITY.md`.

## State Machine runtime truth

- Guard and callback class strings use `new $class()` with no built-in container resolution.
- The event dispatcher is optional at construction time (optional PSR-14 wiring).
- Accessors may no-op or return null for missing properties/methods; do not invent richer behavior.

Use the project slash commands under `.claude/commands/` for focused tasks such as coverage checks, docs sync, CI triage, schema review, release readiness, and security review.
