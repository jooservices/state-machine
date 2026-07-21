# Publishing Workflow Example

```php
$config = [
    'property' => 'status',
    'states' => ['draft', 'review', 'published', 'archived'],
    'initial_state' => 'draft',
    'transitions' => [
        'submit' => ['from' => ['draft'], 'to' => 'review'],
        'approve' => ['from' => ['review'], 'to' => 'published'],
        'reject' => ['from' => ['review'], 'to' => 'draft'],
        'archive' => ['from' => ['published'], 'to' => 'archived'],
    ],
];
```
