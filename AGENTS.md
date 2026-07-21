# JOOservices State Machine Repository Instructions

This repository is a PHP 8.5+ package named `jooservices/state-machine`.

## Core intent

- Preserve the existing package architecture before introducing abstractions.
- Favor minimal changes that fit current modules under `src/`.
- Treat docs and tests as part of the implementation, not follow-up work.
- If requirements are unclear, conflicting, missing, or impossible based on real code, stop and ask the user.
- Do not silently infer, assume, or decide when repository truth is missing or conflicting.

## Architecture and design standards

- Package internals follow the module map under `src/`; do not add Laravel application layers to package internals.
- Laravel application guidance follows `Request -> Controller -> FormRequest -> Service -> Repository -> Model`.
- Controllers stay thin; FormRequests own validation and authorization; Services own business logic; Repositories own query and persistence logic; Models must not become fat business-logic containers.
- Jobs and Commands orchestrate through Services.
- Events are dispatched from Services after successful persistence or commit.
- State machines belong in Services (or dedicated domain services), not Controllers or Models.
- Use enums, constants, or config instead of hard-coded domain values.
- Keep common principles as foundation: SOLID, KISS, DRY, YAGNI, explicit over clever, minimal local change over broad rewrite, and runtime truth over documentation convenience.
- Design patterns belong under architecture/code-structure decisions, must be well-known/common patterns, and must be justified by actual code need.
- Do not invent pseudo-patterns, add unnecessary interface-per-class layers, create manager/helper god classes, use traits as hidden architecture, or force CQRS/Event Sourcing without current need.
- Critical business logic belongs in Services and must not be hidden in Model boot methods, observers, or traits unless explicitly documented and justified.

## Repository quality rules

- Formatting authority: `Pint`
- Narrow PHPDoc cleanup: `PHP-CS-Fixer`
- Structural checks: `PHPCS`
- Static analysis: `PHPStan`
- Maintainability checks: `PHPMD`
- Tests: `PHPUnit`
- No new PHPStan `ignoreErrors` without a narrow scope and clear reason.
- No new PHPMD global exclusions unless proven necessary by real State Machine package code.
- PHPCS must remain structural and must not duplicate Pint formatting.
- Pint wins all formatter conflicts.
- If checker failures represent real code smell, fix code instead of suppressing.
- Any suppression must be reported in final WHAT/WHY/HOW notes.

## Required command map

- `composer lint:fast`
- `composer lint`
- `composer lint:all`
- `composer lint:fix`
- `composer bench`
- `composer bench:quick`
- `composer bench:baseline`
- `composer bench:compare`
- `composer test`
- `composer test:coverage`
- `composer instructions:verify`
- `composer check`
- `composer ci`

Never invent alternative commands such as `composer fix`.

## Agent-first guidance

Before making non-trivial changes, also read:

- `.github/skills/code-style-and-conventions/SKILL.md`
- `.github/skills/architecture-and-design-principles/SKILL.md`
- `.github/skills/class-purpose-and-module-map/SKILL.md`
- `.github/skills/task-routing-and-intent-map/SKILL.md`
- `.github/skills/change-type-taxonomy/SKILL.md`
- `.github/skills/review-and-risk-assessment/SKILL.md`
- `.github/skills/commit-and-pr-authoring/SKILL.md`
- `.github/skills/dependency-and-versioning-policy/SKILL.md`
- `.github/skills/partially-wired-feature-triage/SKILL.md`

These files explain how code should look, how decisions should be made, and which classes/modules own which behaviors.

## Coverage and CI

- Long-term coverage standard is 95% statement coverage.
- CI enforces a 95% minimum statement coverage threshold.
- CI runs `composer audit`, a lint matrix, coverage tests, and optional dependency review on pull requests.
- Release is tag-driven through `vX.Y.Z` tags.

## Hooks and Git hygiene

- CaptainHook validates Conventional Commits on `commit-msg`.
- `pre-commit` runs PHP linting, `gitleaks protect --staged`, `composer lint:pint`, `composer lint:phpcs`, `composer lint:phpstan`, `composer lint:phpmd`, and `composer lint:cs`.
- `pre-push` runs `gitleaks detect` and `composer test`.
- PR titles must use the configured Conventional Commit types and start with an uppercase subject.

## Git branch workflow

- Branch roles:
  - `master`: stable release branch; production-ready code only; accepts approved release PRs and urgent hotfix PRs; no direct commits; tag after release merge.
  - `develop`: integration branch; accepts feature and normal fix PRs; source branch for `release/<version>` branches; receives merge-back from `master` after release.
  - `feature/*`: branch from the latest `develop` for new features; PR back into `develop`.
  - `fix/*`: branch from the latest `develop` for normal bug fixes; PR back into `develop`.
  - `hotfix/*`: only for urgent production fixes; branch from `master`; merge into `master`; then merge back into `develop`.
  - `release/<version>`: branch from the latest `develop`; limited to release metadata and final stabilization; PR into `master`; no unrelated feature work.
- Normal implementation work must start from the latest `develop`.
- Before creating a normal working branch, always:
  - checkout `develop`
  - pull the latest remote changes
  - confirm local `develop` is up to date
- Normal feature or fix flow:

```bash
git checkout develop
git pull origin develop
git checkout -b feature/<short-name>
# or
git checkout -b fix/<short-name>
```

- Normal working branches must open pull requests back into `develop`.
- Release preparation flow:
  - branch `release/<version>` from the latest `develop`
  - update changelog, README, version references, and release metadata on that release branch only
  - open the release PR from `release/<version>` into `master`
  - merge the release PR into `master`
  - create the release tag from `master`
  - merge `master` back into `develop`
- Do not update changelog, README, version references, or release metadata directly on `master`.
- Hotfix work is the only exception to the normal flow and must start from `master`.
- Hotfix branches must open pull requests back into `master`.
- After a hotfix or release is merged into `master`, the same change set must be synchronized back into `develop`.
- Never commit directly to `develop` or `master` except for explicitly approved emergency procedures.
- Keep working branches synced with their parent branch during implementation:
  - normal work rebases regularly onto `develop`
  - hotfix work rebases regularly onto `master`
  - release work stays current with the latest approved `develop` state before PR to `master`
- Before opening a PR, the working branch must be up to date with its parent branch and conflict-free.
- Prefer rebase over merge for routine parent-branch synchronization to keep history clean and reduce conflict risk.
- Branch naming should stay short, readable, and task-oriented, for example:
  - `feature/<short-description>`
  - `fix/<short-description>`
  - `refactor/<short-description>`
  - `hotfix/<short-description>`
  - `release/<version>`
  - `docs/<short-description>`
  - `chore/<short-description>`
- If branch protection is enabled, `develop` and `master` should be protected branches.
- If rebasing requires updating a remote personal branch, prefer `--force-with-lease` only when the team allows force-push on personal branches; otherwise follow the team's remote-history restriction.

## Commit and PR gate

- Keep commits small and logically coherent.
- Do not use vague commit messages such as `fix`, `update`, `change`, `test`, or `temp`.
- Do not create a commit or PR while any required quality gate is failing.
- Before commit and especially before PR, require all repository-defined checks to be clean:
  - tests passing
  - lint passing
  - static analysis passing
  - no unresolved errors, warnings, or notices in the executed checks
  - no leftover debug code, temporary code, dead commented code, unrelated file changes, or formatting noise outside scope
- CI must pass, but CI does not replace local responsibility to run and verify the relevant checks before commit or PR.

## Runtime truth guards

Do not present these behaviors as richer than they currently are:

- Guard and callback class strings are instantiated with `new $class()`; there is no built-in container resolution.
- `psr/event-dispatcher` is required as a type dependency, but the dispatcher itself is optional at construction time.
- `PropertyAccessor` returns `null` for missing or uninitialized properties, silently no-ops writes to missing properties, and throws when writing an already-initialized readonly property.
- `GetterSetterAccessor` returns `null` when no getter exists and silently no-ops when no setter exists.
- When the subject property is null/uninitialized, `StateMachine::getState()` falls back to `initial_state`.
- Multiple graphs are supported only as separate `StateMachine` instances on the same subject, not as a single multi-graph object.
- There is no persistence layer, database adapter, or framework service provider in this package.

Also keep in mind:

- Config is validated eagerly in `StateMachineConfig::fromArray()` and `TransitionConfig::fromArray()`.
- Transition failure events fire only when state writing throws after guards and before-callbacks have already run.
- Missing guard classes reject transitions; missing callback classes are skipped.

## Documentation policy

- Use the canonical product name `JOOservices State Machine`.
- Use `jooservices/state-machine` only for the Composer package identifier.
- Do not document behavior that is only declared in code but not wired into runtime behavior.
- When public behavior changes, update docs and examples in the same change.

## Community governance policy

- Keep `SECURITY.md`, `CODE_OF_CONDUCT.md`, `CONTRIBUTING.md`, and `.github/ISSUE_TEMPLATE/` plus `.github/pull_request_template.md` aligned with current repository workflow.
- Security vulnerabilities must not be routed through public issues; follow `SECURITY.md`.
- Issues and pull requests should use the repository templates when those templates exist.
- If governance files, templates, docs, or AI instructions disagree, stop and ask instead of guessing.

## Change checklist

Before considering a task done:

- confirm the owning module under `src/`
- add or update unit/integration coverage for behavior changes
- run the relevant local gates (`composer lint:fast` or `composer check`)
- update docs when public API or runtime truth changes
- keep AI instruction adapters in sync when policy changes (`composer instructions:verify`)
