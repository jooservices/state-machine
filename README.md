# jooservices/state-machine

[![CI](https://github.com/jooservices/state-machine/actions/workflows/ci.yml/badge.svg?branch=develop)](https://github.com/jooservices/state-machine/actions/workflows/ci.yml)
[![Coverage (develop)](https://codecov.io/gh/jooservices/state-machine/branch/develop/graph/badge.svg)](https://codecov.io/gh/jooservices/state-machine/branch/develop)
[![Quality Gate (master)](https://sonarcloud.io/api/project_badges/measure?project=jooservices_state-machine&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=jooservices_state-machine)
[![OpenSSF Scorecard](https://api.securityscorecards.dev/projects/github.com/jooservices/state-machine/badge)](https://securityscorecards.dev/viewer/?uri=github.com/jooservices/state-machine)
[![PHP Version](https://img.shields.io/badge/PHP-8.5%2B-blue.svg)](https://www.php.net/)
[![GitHub Release](https://img.shields.io/github/v/release/jooservices/state-machine?display_name=tag)](https://github.com/jooservices/state-machine/releases)
[![Packagist Version](https://img.shields.io/packagist/v/jooservices/state-machine)](https://packagist.org/packages/jooservices/state-machine)
[![Total Downloads](https://img.shields.io/packagist/dt/jooservices/state-machine)](https://packagist.org/packages/jooservices/state-machine)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

The **JOOservices State Machine** is a PHP `^8.5` configuration-driven finite state machine for any PHP object — DTOs, POPOs, or framework models. Zero framework coupling. State is a string property on the subject.

Latest stable release: **`v4.0.0`** — no backward compatibility with the retired `v1.x` archive.

## Features

- configuration-driven graphs validated at construction time
- `can()` / `apply()` / `getAvailableTransitions()`
- pluggable state accessors (property reflection or getter/setter)
- guards and before/after callbacks as class strings
- optional PSR-14 lifecycle events
- multiple independent graphs per subject (separate machine instances)
- pure PHP `^8.5` with no Laravel/Symfony runtime requirement

## Requirements

- PHP `^8.5`
- Composer 2.x

## Installation

```bash
composer require jooservices/state-machine:^4.0
```

## Quick start

```php
use JOOservices\StateMachine\StateMachineFactory;

final class Order
{
    public function __construct(
        public string $status = 'pending',
    ) {}
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
$machine = (new StateMachineFactory())->create($order, 'order', $config);

if ($machine->can('confirm')) {
    $machine->apply('confirm');
}

echo $machine->getState(); // confirmed
```

## Design notes

- guards and callbacks are constructed with `new $class()` (no container resolution)
- guard/callback class-strings are validated at config construction (must exist and implement the contract)
- accessors throw `StateAccessException` when the write target is missing or readonly
- event dispatcher is optional; consumers bring their own PSR-14 implementation
- no built-in persistence, Eloquent casts, or service providers

## Documentation

Start with:

- [Documentation Hub](./docs/README.md)
- [Changelog](./CHANGELOG.md)
- [Support](./SUPPORT.md)
- [Governance](./GOVERNANCE.md)
- [Workflows](./WORKFLOWS.md)
- phpDocumentor config: [`phpdoc.dist.xml`](./phpdoc.dist.xml)
- [Installation](./docs/01-getting-started/01-installation.md)
- [Quick Start](./docs/01-getting-started/02-quick-start.md)

## Development

```bash
make validate
make install
make lint
make test
make ci
```

Or with Composer inside Docker / host PHP 8.5:

```bash
composer validate --strict
composer lint
composer lint:all
composer test
composer test:coverage
composer check
composer ci
```

Contributor workflow details live in:

- [Setup](./docs/04-development/01-setup.md)
- [Contributing](./docs/04-development/07-contributing.md)
- [CI/CD](./docs/04-development/05-ci-cd.md)
- [Release Process](./docs/04-development/06-release-process.md)

Branch, release, CI, and integration details are documented in
[WORKFLOWS.md](./WORKFLOWS.md) and the linked development guides.

## Community

- [Contributing](./CONTRIBUTING.md)
- [Security Policy](./SECURITY.md)
- [Code of Conduct](./CODE_OF_CONDUCT.md)
- [Support](./SUPPORT.md)
- [Governance](./GOVERNANCE.md)

## License

This project is licensed under the [MIT License](./LICENSE).
