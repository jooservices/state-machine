# AI Skills

This repository includes an AI skill pack for agents working in `jooservices/state-machine`.

## Purpose

The AI skill pack helps agents and contributors:

- follow repository-native code style and design principles
- understand class and module ownership before editing
- route tasks to the right workflow
- classify changes correctly
- review risk and compatibility impact
- respect lint, coverage, CI, release, security, and hook policy
- avoid claiming support for partially wired features

## Entry points

Start with:

- [`AGENTS.md`](../../AGENTS.md)
- [`CLAUDE.md`](../../CLAUDE.md)
- [`ai/skills/README.md`](../../ai/skills/README.md)
- [`ai/skills/USAGE.md`](../../ai/skills/USAGE.md)

## Canonical skill source

The source of truth lives in:

- `.github/skills/`

Treat those canonical skill files as the first place to update when repository behavior, workflows, commands, or runtime limitations change.

Those canonical skills are then adapted for:

- Cursor rules in `.cursor/rules/`
- Claude Code commands in `.claude/commands/`
- VS Code Copilot instructions and prompts in `.github/instructions/`, `.github/prompts/`, and `.github/copilot-instructions.md`
- JetBrains prompt templates in `jetbrains/prompts/`
- Antigravity portable prompts in `antigravity/prompts/`

## Recommended workflow

1. Read `AGENTS.md`.
2. Route the task with the task-routing skill.
3. Classify the change type.
4. Load the task-specific skills from `.github/skills/`.
5. Implement or review the change.
6. Re-check tests, docs, compatibility, and release impact.

## Practical rules for agents

Agents should:

- treat `composer.json`, `captainhook.json`, `.github/workflows/`, and `src/` as source-of-truth files
- keep benchmark guidance in sync with `composer.json` scripts, `docs/04-development/04-testing.md`, and `tests/Benchmark/`
- keep docs honest about declared-but-unwired attributes and other runtime gaps
- prefer small corrections, clarifications, and sync updates over broad documentation rewrites
- update `.github/skills/` before changing prompt adapters in `.claude/commands/`, `.github/prompts/`, `jetbrains/prompts/`, or `antigravity/prompts/`
- keep `SECURITY.md`, `CODE_OF_CONDUCT.md`, `CONTRIBUTING.md`, issue templates, and the pull request template aligned with repository workflow when they are touched
- route vulnerability reporting through `SECURITY.md`, not public issues
- follow the repository Git workflow:
  - normal work starts from latest `develop`
  - release preparation branches from latest `develop` as `release/<version>`
  - release PRs target `master`
  - release metadata changes stay on `release/<version>`, not directly on `master`
  - hotfix work starts from `master`
  - PR target matches the parent branch
  - `master` merges back into `develop` after release or hotfix completion
  - working branches stay synchronized with the parent branch
  - rebasing is preferred for routine parent-branch sync
  - required quality gates must be clean before commit or PR

Agents should not:

- document behavior that only exists as declared code without runtime wiring and tests
- treat prompt adapters as canonical if they disagree with `.github/skills/`
- silently smooth over ambiguity; mark it as `Needs clarification`, `Missing decision`, `Assumption`, or `Outdated / should be verified`
- suggest direct commits to `develop` or `master`
- suggest skipping pull-latest or parent-branch sync steps
- suggest opening a PR with failing or noisy quality gates

## Maintenance rule

When repository behavior changes, update `.github/skills/` first, then sync the adapter layers.

When quality commands or benchmark workflows change, sync the command references in `AGENTS.md`, this document, and `ai/skills/README.md` in the same change.

When Git flow rules change, sync `AGENTS.md`, contributor docs, release docs, canonical `.github/skills/`, and release-readiness prompt adapters in the same change.
