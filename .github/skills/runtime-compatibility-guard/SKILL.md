---
name: runtime-compatibility-guard
description: "Use when: changing runtime behavior, public APIs, hydration, validation, normalization, mutation semantics, or schema output; reviewing backward compatibility; or guarding against unsupported-feature drift."
---

# Runtime Compatibility Guard Skill

## Focus

Use this skill whenever a change can affect public behavior or developer expectations.

## Guardrails

- Preserve existing public behavior unless the change intentionally revises it
- Prefer additive changes over silent behavioral rewrites
- Add regression tests for bug fixes
- Update docs/examples when behavior changes

## Current limitations that must stay explicit

- Deprecated placeholder attributes still include `Computed`, `Deprecated`, and `OptionalProperty` (not runtime-active)
- `Context::$transformerMode` remains reserved and is not behavior-defining
- `DefaultFrom` and class-level `DiscriminatorMap` are wired into hydration behavior and should be documented as active features
- Property-level `#[Pipeline]`, `Context::$globalPipeline`, and `#[StrictType]` are already wired into runtime behavior and should not be described as placeholders
- `CastWith` / `TransformWith` options are constructor-spread arguments, not free-form method config bags
- Native PHP union types are cast through union-aware member selection; intersection types remain pass-through at runtime
- Validation failures from `from()` can surface as top-level `ValidationException` when all nested errors are validation failures
- Hydration, normalization, and mutation share one engine slot per DTO class
- Typed DTO arrays can be inferred from common PHPDoc forms, but unsupported annotations should still be documented carefully
- `Data::update()` and `Data::set()` rebuild through hydration, so mutation semantics can now fail fast on invalid patches
- `JdtoException::setPayloadRedactor()` is available for redacting sensitive values in exception `toArray()` payloads
- Schema generation expands common nested DTO and typed-array shapes, but still stops short of a full reference-based graph

## Review checklist

1. Does the change alter hydration or casting behavior?
2. Does the change affect validation timing or error messages?
3. Does the change affect normalization or serialization output?
4. Does the change alter mutability or update semantics?
5. Does the change require docs, examples, or release notes?
6. Does the change need integration coverage rather than unit coverage only?

## Definition of done

- Public behavior impact is understood and tested
- Unsupported features are not accidentally marketed as supported
- Compatibility notes are reflected in docs when needed

## State Machine runtime truth

- Guard and callback class strings use `new $class()` with no built-in container resolution.
- The event dispatcher is optional at construction time (optional PSR-14 wiring).
- Accessors may no-op or return null for missing properties/methods; do not invent richer behavior.
- Document only wired runtime behavior; stop and ask when repository truth is missing.
