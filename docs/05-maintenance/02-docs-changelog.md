# Documentation Changelog

## 2026-07-21

v1.3.0 global pipeline and runtime-truth documentation sync.

Highlights:

- documented active `Context::$globalPipeline` behavior and order relative to property `#[Pipeline]`
- clarified `CastWith` / `TransformWith` options as constructor-spread arguments
- moved deprecated placeholders and reserved `transformerMode` into explicit non-behavior-defining guidance
- synchronized README, AGENTS, skills, architecture docs, user guides, troubleshooting, and input behavior matrix

## 2026-07-05

v1.2.0 audit follow-up documentation sync.

Highlights:

- added [Input Behavior Matrix](../02-user-guide/13-input-behavior-matrix.md) for hydration, strict mode, defaults, validation, and exception rules
- corrected architecture docs to describe the unified per-class engine instead of three separate engine slots
- updated validation, utility, serialization, type-casting, data-object, and troubleshooting guides for `ValidationException`, `with()` rehydration, `mergeRecursive()` semantics, union casting, `maxDepth` failures, and exception payload redaction
- marked placeholder attributes and internal dead-code helpers as deprecated in risks and advanced-attribute docs
- synchronized AGENTS runtime-truth guards and the runtime compatibility skill with v1.2.0 behavior

## 2026-05-11

Git-flow documentation and AI-guidance alignment update.

Highlights:

- standardized the approved Git flow across contributor docs, AI instructions, and canonical skills
- documented `release/<version>` as the required release-preparation branch from `develop`
- removed stale guidance that treated the release source branch as undefined
- clarified that release metadata changes belong on release branches, not directly on `master`
- documented the required `master` back-merge into `develop` after release or hotfix completion
- narrowed CI and release security audit docs to locked non-dev dependencies so abandoned dev-only tools do not block PR or tag validation

## 2026-05-02

Audit follow-up documentation and workflow sync.

Highlights:

- updated release documentation to match the stricter release workflow validation gate
- aligned secret-scanning docs with the `origin/master` pre-push range used by CaptainHook
- corrected the documentation hub schema summary to mention common nested DTO and typed-array expansion
- refreshed AI module guidance for `Data::update()` and `Data::set()` hydration-backed mutation behavior

## 2026-04-20

Targeted contributor-doc sync update.

Highlights:

- aligned `AGENTS.md` and development docs with the current CaptainHook pre-commit checks, including `PHPMD` and `PHP-CS-Fixer`
- updated secret-scanning docs to remove the stale `origin/main` pre-push example and match the current `gitleaks detect` hook command
- corrected release workflow docs so `release.yml` reflects the current validate job sequence of audit, lint, and test steps

## 2026-04-15

Targeted documentation and AI-guidance sync update.

Highlights:

- corrected CI and architecture docs to reflect the current conditional SonarQube Cloud job
- strengthened AI-skill usage guidance around canonical skill ownership and source-of-truth files
- updated docs-sync prompt adapters to prefer audit-first, minimal-change documentation maintenance
- flagged the current `origin/main` gitleaks pre-push reference as needing clarification because the repository default branch is `master`
- clarified that release automation is tag-driven and does not itself define a required release branch
- added explicit Git branching workflow rules for normal work, hotfixes, rebases, PR targets, and clean quality gates

## 2026-04-02

Repository-backed overhaul of the `docs/` tree for the **JOOservices State Machine**.

Highlights:

- standardized the canonical product name to `JOOservices State Machine`
- replaced inconsistent package-name-as-product-name usage
- removed or corrected unsupported claims around pipelines, strict type overrides, discriminator maps, typed DTO arrays, and automatic `Optional` hydration
- rewrote getting-started, user-guide, examples, architecture, and development docs against the current code, tests, and GitHub workflows
- added contributor and release-process guides

## 2026-03-08

Previous architecture and process documentation expansion across `00-architecture/` and `04-development/`.
