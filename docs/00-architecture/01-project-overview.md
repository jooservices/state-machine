# Project Overview

`jooservices/state-machine` is a pure PHP 8.5+ library that applies configuration-driven finite state machines to any object.

## Goals

- zero framework coupling
- explicit, validated configuration
- testable guards and callbacks
- optional PSR-14 events
- quality, docs, and AI tooling parity with `jooservices/dto`

## Non-goals

- database-backed state tables
- workflow engines with human tasks
- framework-specific service providers (may live in separate packages later)
