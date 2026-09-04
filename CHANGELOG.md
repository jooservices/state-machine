# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/v2.0.0.html).

> [!WARNING]
> **Public line starts at `v4.0.0`.** The retired `v1.x` archive is not a
> compatibility ancestor of this release.

## [Unreleased]

## [4.0.0] - 2026-09-04

### Added

- `StateAccessException` for fail-loud state writes
- Config-time validation that guard/callback class-strings exist and implement their contracts
- Docker tooling (`Dockerfile`, `docker-compose.yml`, `Makefile`) for local PHP 8.5 work
- Root docs: `SUPPORT.md`, `GOVERNANCE.md`, `WORKFLOWS.md`
- Commitlint workflow + `CODEOWNERS` + final `CI Gate` job

### Changed

- First public line **`v4.0.0`** — no compatibility with retired `v1.x`
- PHP constraint `^8.5`
- `StateMachine`, `StateMachineFactory`, and accessors are `final`
- `apply()` order: `TransitionStarting` → before callbacks → write → after callbacks → `TransitionCompleted`
- `TransitionFailed` fires for any throwable after starting (before/write/after)
- Accessors throw on missing write targets instead of silent no-op

### Removed

- Nested AI adapter trees (`.claude`, `.cursor`, `ai/`, `antigravity`, `CLAUDE.md`, `jetbrains`) from the package repo
- Runtime silent skip of missing callback classes

## [1.0.0] - 2026-07-20

### Added

- Initial stable release of `jooservices/state-machine` (retired line; superseded by v4)
- Configuration-driven finite state machine engine (`StateMachine`, `StateMachineFactory`)
- Immutable config value objects (`StateMachineConfig`, `TransitionConfig`) with eager validation
- Pluggable state accessors (`PropertyAccessor`, `GetterSetterAccessor`)
- Guard and callback contracts with class-string wiring
- Optional PSR-14 transition lifecycle events (`TransitionStarting`, `TransitionCompleted`, `TransitionFailed`)
- Domain exceptions for invalid configuration, invalid transitions, and guard rejection
- Multi-graph support via separate machine instances on one subject
- Test suite (unit + integration), PHPBench harness, and 95% statement coverage gate

[Unreleased]: https://github.com/jooservices/state-machine/compare/v4.0.0...HEAD
[4.0.0]: https://github.com/jooservices/state-machine/releases/tag/v4.0.0
[1.0.0]: https://github.com/jooservices/state-machine/releases/tag/v1.0.0
