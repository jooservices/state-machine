# State Accessors

## PropertyAccessor (default)

Reads/writes public (including promoted) properties via reflection.

Already-initialized `readonly` properties cannot be rewritten; the accessor throws `RuntimeException`. Use a mutable property or `GetterSetterAccessor` when state must change.

## GetterSetterAccessor

Uses `get{Property}` / `set{Property}` methods, with fallback to a property-named getter method.

```php
use JOOservices\StateMachine\Accessors\GetterSetterAccessor;
use JOOservices\StateMachine\StateMachineFactory;

$factory = new StateMachineFactory(defaultAccessor: new GetterSetterAccessor());
```
