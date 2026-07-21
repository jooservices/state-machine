---
name: class-purpose-and-module-map
description: "Use when: orienting inside the codebase; understanding which class or module owns a behavior; deciding where to implement a change; and preventing cross-layer edits that belong elsewhere."
---

# Class Purpose and Module Map Skill

## Purpose

This skill helps agents understand what each major class and module is for before they edit anything.

## Public foundation classes

### `StateMachine`

- Core finite state machine engine bound to one subject + one graph
- Owns `can()`, `apply()`, `getState()`, `getAvailableTransitions()`, and transition context helpers
- Coordinates guards, callbacks, state writes, and optional PSR-14 events

### `StateMachineFactory`

- Consumer-facing factory for building machines from raw config arrays
- Applies default accessor and optional event dispatcher defaults

### `TransitionContext`

- Immutable value object passed to guards, callbacks, and events
- Carries subject, transition name, from/to states, and metadata

## Configuration classes

### `Config/StateMachineConfig`

- Immutable validated graph configuration
- Owns states list, property name, initial state, and named transitions
- Validates config eagerly via `fromArray()`

### `Config/TransitionConfig`

- Immutable single-transition definition
- Owns from states, to state, guards, and before/after callbacks

## Contracts

### `Contracts/StateMachineInterface`

- Public API surface for machine consumers

### `Contracts/StateAccessorInterface`

- Strategy for reading/writing the subject state property

### `Contracts/GuardInterface`

- Pre-transition allow/deny checks

### `Contracts/CallbackInterface`

- Before/after transition side-effect hooks

## Accessors

### `Accessors/PropertyAccessor`

- Reflection-based public/promoted property access
- Default factory accessor

### `Accessors/GetterSetterAccessor`

- Conventional `getX` / `setX` (and bare property-named getters) access

## Events

### `Events/TransitionStarting`

- Dispatched after guards/before-callbacks succeed and before state is written

### `Events/TransitionCompleted`

- Dispatched after successful state write and after-callbacks

### `Events/TransitionFailed`

- Dispatched when state writing throws

## Exceptions

### `Exceptions/StateMachineException`

- Base package exception

### `Exceptions/InvalidConfigurationException`

- Malformed config arrays

### `Exceptions/InvalidTransitionException`

- Undefined or currently unavailable transitions

### `Exceptions/TransitionGuardException`

- Guard rejection during `apply()`

## Placement heuristics

- Change `StateMachine` when transition orchestration, guard/callback ordering, or event timing changes
- Change `Config/*` when configuration schema or validation rules change
- Change `Accessors/*` when state storage strategy changes
- Change `Contracts/*` when public extension points change
- Change `Events/*` when lifecycle event payloads change
- Change `Exceptions/*` when error taxonomy or messages change
- Prefer new guard/callback classes in consuming apps over package core changes for domain rules

## Definition of done

- The agent can name the owning module before editing
- Code changes land in the layer responsible for the behavior
- Public foundation classes are edited more carefully than internal helpers
