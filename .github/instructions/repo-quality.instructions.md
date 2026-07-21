---
applyTo: "**/*"
description: Repository-wide quality, compatibility, CI, docs, and hook rules
---

- This repository is the `JOOservices State Machine` package `jooservices/state-machine`.
- Match repository-native style, class shape, and naming conventions.
- Understand module ownership before editing core behavior.
- Prefer minimal, additive, compatibility-safe changes.
- If requirements are unclear, conflicting, missing, or impossible based on real code, stop and ask the user.
- Do not silently infer, assume, or decide when repository truth is missing or conflicting.
- Use `Pint` as the primary formatting authority.
- Keep `PHP-CS-Fixer` to its narrow PHPDoc-cleanup role.
- Expect `PHPCS`, `PHPStan`, `PHPMD`, PHPUnit, gitleaks, and `composer audit` to matter.
- Keep PHPCS structural only, PHPStan at max or strictest feasible level, and PHPMD focused on maintainability.
- Do not add PHPStan `ignoreErrors` or PHPMD global exclusions without narrow scope and clear reason.
- Fix real checker failures in code instead of suppressing them, and report any suppression in final WHAT/WHY/HOW notes.
- CI enforces the 95% minimum statement coverage standard.
- Laravel application guidance follows `Request -> Controller -> FormRequest -> Service -> Repository -> Model`, with Services owning business logic, transactions, and event dispatch.
- Commit messages use Conventional Commits.
- PR titles must use allowed Conventional Commit types and start with an uppercase subject.
- Do not present partially wired runtime features as fully supported.
- Update docs and examples when public behavior changes.

## State Machine runtime truth

- Guard and callback class strings use `new $class()` with no built-in container resolution.
- The event dispatcher is optional at construction time (optional PSR-14 wiring).
- Accessors may no-op or return null for missing properties/methods; do not invent richer behavior.
- Document only wired runtime behavior; stop and ask when repository truth is missing.
