# Payment States Example

```php
$paymentConfig = [
    'property' => 'paymentStatus',
    'states' => ['unpaid', 'paid', 'refunded'],
    'initial_state' => 'unpaid',
    'transitions' => [
        'pay' => ['from' => ['unpaid'], 'to' => 'paid'],
        'refund' => ['from' => ['paid'], 'to' => 'refunded'],
    ],
];

$fulfillmentConfig = [
    'property' => 'fulfillmentStatus',
    'states' => ['unfulfilled', 'fulfilled'],
    'initial_state' => 'unfulfilled',
    'transitions' => [
        'fulfill' => ['from' => ['unfulfilled'], 'to' => 'fulfilled'],
    ],
];
```

See also [Multiple State Machines](../02-user-guide/06-multiple-state-machines.md).
