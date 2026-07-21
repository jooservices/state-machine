# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2026-07-20

### Added

- Initial stable release of `jooservices/state-machine`
- Configuration-driven finite state machine engine (`StateMachine`, `StateMachineFactory`)
- Immutable config value objects (`StateMachineConfig`, `TransitionConfig`) with eager validation
- Pluggable state accessors (`PropertyAccessor`, `GetterSetterAccessor`)
- Guard and callback contracts with class-string wiring
- Optional PSR-14 transition lifecycle events (`TransitionStarting`, `TransitionCompleted`, `TransitionFailed`)
- Domain exceptions for invalid configuration, invalid transitions, and guard rejection
- Multi-graph support via separate machine instances on one subject
- Test suite (unit + integration), PHPBench harness, and 95% statement coverage gate
- DTO-aligned quality tooling: Pint, PHPCS, PHPStan, PHPMD, PHP-CS-Fixer, CaptainHook, gitleaks
- AI agent instructions, skills, prompts, CI workflows, and documentation hub
