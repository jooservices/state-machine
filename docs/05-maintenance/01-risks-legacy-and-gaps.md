# Risks, Legacy, and Gaps

## Current runtime limitations

- Guards/callbacks are not container-resolved (`new $class()` only).
- Accessors fail soft for missing properties/methods instead of always throwing.
- `PropertyAccessor` cannot mutate already-initialized `readonly` properties; prefer mutable state fields or `GetterSetterAccessor`.
- `TransitionFailed` is only dispatched when state writing throws, not when guards reject.
- No framework bridge (Laravel provider, Symfony bundle) ships in this package.
- No built-in history/audit trail or persistence adapter.

## Compatibility notes

- PHP `>=8.5` is required.
- Package public API is the contracts, factory, machine methods, config array shape, and event classes.

## Planned improvements (not promises)

- optional container-aware guard/callback resolver
- stricter accessor error modes
- official Laravel integration package if demand exists
