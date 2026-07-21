# Order Workflow Example

```php
use JOOservices\StateMachine\StateMachineFactory;

final class Order
{
    public function __construct(public string $status = 'pending') {}
}

$config = [
    'property' => 'status',
    'states' => ['pending', 'confirmed', 'shipped', 'cancelled'],
    'initial_state' => 'pending',
    'transitions' => [
        'confirm' => ['from' => ['pending'], 'to' => 'confirmed'],
        'ship' => ['from' => ['confirmed'], 'to' => 'shipped'],
        'cancel' => ['from' => ['pending', 'confirmed'], 'to' => 'cancelled'],
    ],
];

$order = new Order();
$sm = (new StateMachineFactory())->create($order, 'order', $config);
$sm->apply('confirm');
$sm->apply('ship');
```
