# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Initial `jooservices/state-machine` package scaffold
- Configuration-driven finite state machine engine (`StateMachine`, `StateMachineFactory`)
- Immutable config value objects (`StateMachineConfig`, `TransitionConfig`)
- Pluggable state accessors (`PropertyAccessor`, `GetterSetterAccessor`)
- Guard and callback contracts with class-string wiring
- Optional PSR-14 transition lifecycle events
- Domain exceptions for invalid configuration, invalid transitions, and guard rejection
- Test suite (unit, integration), PHPBench harness, and DTO-aligned quality tooling
- AI agent instructions, CI workflows, CaptainHook hooks, and documentation hub

