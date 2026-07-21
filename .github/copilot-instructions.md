# Copilot Instructions For `jooservices/state-machine`

Read [AGENTS.md](AGENTS.md) as the primary repository policy.

When generating or editing code:

- prefer the existing package architecture over new abstractions
- match repository-native style and naming, not just formatter output
- understand which class or module owns the behavior before editing
- stop and ask the user if requirements are unclear, conflicting, missing, or impossible based on real code
- keep tests and docs in the same change when public behavior moves
- respect current runtime limitations from `docs/05-maintenance/01-risks-legacy-and-gaps.md`
- assume local hooks and CI will enforce linting, coverage, security, and commit hygiene
- keep Pint as the formatting authority; PHPCS is structural, PHPStan stays max or strictest feasible, PHPMD covers maintainability, and suppressions require narrow scope plus a clear reason
- do not silently add PHPStan `ignoreErrors` or PHPMD global exclusions; fix real checker failures in code and report any suppression in final WHAT/WHY/HOW notes
- follow the approved Git flow in `AGENTS.md`: normal work from `develop`, release preparation on `release/<version>` from `develop`, release PRs into `master`, then merge `master` back into `develop`
- keep governance files and GitHub issue or PR templates aligned with current repository workflow when they are touched
- do not route vulnerability handling through public issues; follow `SECURITY.md`

Use prompt files in `.github/prompts/` for focused tasks.

## State Machine runtime truth

- Guard and callback class strings use `new $class()` with no built-in container resolution.
- The event dispatcher is optional at construction time (optional PSR-14 wiring).
- Accessors may no-op or return null for missing properties/methods; do not invent richer behavior.
- Document only wired runtime behavior; stop and ask when repository truth is missing.
