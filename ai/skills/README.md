# AI Skills Map

This repository keeps one quality policy and exposes it through several AI tool formats.

See also:

- [AI Skills Usage Guide](./USAGE.md)

## Canonical repository skills

- `.github/skills/repo-quality-foundation/SKILL.md`
- `.github/skills/code-style-and-conventions/SKILL.md`
- `.github/skills/architecture-and-design-principles/SKILL.md`
- `.github/skills/class-purpose-and-module-map/SKILL.md`
- `.github/skills/task-routing-and-intent-map/SKILL.md`
- `.github/skills/change-type-taxonomy/SKILL.md`
- `.github/skills/review-and-risk-assessment/SKILL.md`
- `.github/skills/commit-and-pr-authoring/SKILL.md`
- `.github/skills/dependency-and-versioning-policy/SKILL.md`
- `.github/skills/partially-wired-feature-triage/SKILL.md`
- `.github/skills/php-package-development/SKILL.md`
- `.github/skills/coverage-and-lint-guard/SKILL.md`
- `.github/skills/runtime-compatibility-guard/SKILL.md`
- `.github/skills/documentation-sync/SKILL.md`
- `.github/skills/ci-hooks-maintenance/SKILL.md`
- `.github/skills/security-hardening/SKILL.md`
- `.github/skills/schema-evolution/SKILL.md`
- `.github/skills/release-management/SKILL.md`

## Adapter layers

- `AGENTS.md`: shared always-on repository policy
- `.cursor/rules/`: Cursor project rules
- `CLAUDE.md` and `.claude/commands/`: Claude Code guidance and slash commands
- `.github/copilot-instructions.md`, `.github/instructions/`, `.github/prompts/`: VS Code Copilot instructions and prompt files
- `jetbrains/prompts/`: prompt-library-ready markdown templates
- `antigravity/prompts/`: portable prompts for environments without a stable checked-in skill format

## Intent

All adapters should reflect the same repository truth:

- code style and conventions beyond formatter output
- design principles and change heuristics for agents
- class and module ownership
- stop-and-ask behavior when requirements are unclear, conflicting, missing, or impossible based on real code
- task routing and change classification
- review, risk, and PR authoring
- dependency, versioning, and partially wired feature policy
- style and static analysis gates
- no new PHPStan `ignoreErrors` without a narrow scope and clear reason
- no new PHPMD global exclusions unless proven necessary
- PHPCS structural-only checks, with Pint winning formatter conflicts
- real checker failures fixed in code instead of hidden by suppressions
- 95% minimum statement coverage standard enforced by CI
- benchmark command and `tests/Benchmark` performance probes
- git hook and PR hygiene
- CI and release policy
- security checks
- documentation sync
- runtime compatibility and supported-feature boundaries
- approved Git flow with `develop` integration, `release/<version>` release preparation, `master` release tagging, and `master` back-merge into `develop`

## State Machine runtime truth

- Guard and callback class strings use `new $class()` with no built-in container resolution.
- The event dispatcher is optional at construction time (optional PSR-14 wiring).
- Accessors may no-op or return null for missing properties/methods; do not invent richer behavior.
- Document only wired runtime behavior; stop and ask when repository truth is missing.
