# Guards

Implement `GuardInterface`:

```php
use JOOservices\StateMachine\Contracts\GuardInterface;
use JOOservices\StateMachine\TransitionContext;

final class PaidInFullGuard implements GuardInterface
{
    public function check(TransitionContext $context): bool
    {
        return ($context->metadata['paid'] ?? false) === true;
    }
}
```

Wire as class strings:

```php
'guards' => [PaidInFullGuard::class],
```

Runtime truth: each guard is created with `new $class()`. Missing guard classes reject the transition.
