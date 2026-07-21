# Quick Start

```php
use JOOservices\StateMachine\StateMachineFactory;

$subject = new class {
    public string $status = 'draft';
};

$config = [
    'property' => 'status',
    'states' => ['draft', 'published', 'archived'],
    'initial_state' => 'draft',
    'transitions' => [
        'publish' => ['from' => ['draft'], 'to' => 'published'],
        'archive' => ['from' => ['published'], 'to' => 'archived'],
    ],
];

$machine = (new StateMachineFactory())->create($subject, 'content', $config);
$machine->apply('publish');

assert($machine->getState() === 'published');
```
