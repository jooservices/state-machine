# Template: Add State Accessor

1. Implement `StateAccessorInterface` with `getState` / `setState`.
2. Prefer pure PHP reflection or method conventions; avoid framework coupling.
3. Pass via `StateMachineFactory` constructor or `create(... $accessor)`.
4. Add unit tests for read/write and missing-property edge cases.
5. Document in `docs/02-user-guide/07-state-accessors.md`.
