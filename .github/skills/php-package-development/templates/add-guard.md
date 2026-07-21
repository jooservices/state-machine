# Template: Add Guard

1. Create a class implementing `JOOservices\StateMachine\Contracts\GuardInterface`.
2. Implement `check(TransitionContext $context): bool`.
3. Wire it as a class-string under transition `guards` in config.
4. Add unit/integration coverage for pass and fail paths.
5. Document usage in `docs/02-user-guide/03-guards.md` if public guidance changes.

Runtime truth: guards are instantiated with `new $class()` — no container resolution.
