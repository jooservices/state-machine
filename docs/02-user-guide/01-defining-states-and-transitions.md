# Defining States and Transitions

```php
$config = [
    'property' => 'status',
    'states' => ['pending', 'confirmed', 'cancelled'],
    'initial_state' => 'pending',
    'transitions' => [
        'confirm' => [
            'from' => ['pending'],
            'to' => 'confirmed',
        ],
        'cancel' => [
            'from' => ['pending', 'confirmed'],
            'to' => 'cancelled',
            'guards' => [MyGuard::class],
            'callbacks' => [
                'before' => [AuditBefore::class],
                'after' => [NotifyAfter::class],
            ],
        ],
    ],
];
```

Invalid configs throw `InvalidConfigurationException` immediately.
