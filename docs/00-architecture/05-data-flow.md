# Data Flow

1. Consumer builds a config array.
2. `StateMachineConfig::fromArray()` validates states, initial state, and transitions.
3. `StateMachineFactory` binds subject + accessor + optional dispatcher.
4. `can($transition)` checks from-state membership and guards.
5. `apply($transition)`:
   - re-validates availability
   - runs guards (throw on reject)
   - runs before-callbacks
   - dispatches `TransitionStarting`
   - writes new state via accessor
   - on write failure: dispatches `TransitionFailed` and rethrows
   - runs after-callbacks
   - dispatches `TransitionCompleted`
