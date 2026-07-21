# Template: Add Callback

1. Create a class implementing `JOOservices\StateMachine\Contracts\CallbackInterface`.
2. Implement `execute(TransitionContext $context): void`.
3. Wire under `callbacks.before` or `callbacks.after`.
4. Cover ordering relative to state write and events.
5. Document in `docs/02-user-guide/04-callbacks.md` when needed.

Runtime truth: missing callback classes are skipped; before-callbacks run before state write.
