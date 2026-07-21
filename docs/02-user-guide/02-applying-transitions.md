# Applying Transitions

```php
if ($machine->can('confirm')) {
    $machine->apply('confirm', ['actor' => $userId]);
}

$available = $machine->getAvailableTransitions();
$context = $machine->getTransitionContext('confirm');
```

## Exceptions

- `InvalidTransitionException` when the transition is undefined or not available from the current state
- `TransitionGuardException` when a guard rejects `apply()`
