---
name: schema-evolution
description: "Use when: evolving state machine configuration schema keys, transition shape, or validation rules safely."
---

# Config Schema Evolution Skill

## Purpose

Protect consumers when `StateMachineConfig` / `TransitionConfig` array schema changes.

## Rules

- Validate eagerly in `fromArray()`; fail closed with `InvalidConfigurationException`.
- Prefer additive optional keys over breaking renames.
- When renaming keys, document migration and keep temporary dual-read only if explicitly required.
- Update unit tests for missing/invalid keys and docs examples in the same change.
- Do not invent config keys that are not wired into runtime.

## Checklist

- [ ] Config validation coverage
- [ ] Integration coverage for any behavioral key
- [ ] Docs/examples updated
- [ ] Changelog entry for public config changes
