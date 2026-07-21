# Callbacks

Implement `CallbackInterface`:

```php
use JOOservices\StateMachine\Contracts\CallbackInterface;
use JOOservices\StateMachine\TransitionContext;

final class LogTransition implements CallbackInterface
{
    public function execute(TransitionContext $context): void
    {
        // side effects
    }
}
```

```php
'callbacks' => [
    'before' => [LogTransition::class],
    'after' => [LogTransition::class],
],
```

Before-callbacks run before the state write; after-callbacks run only on successful write. Missing callback classes are skipped.
