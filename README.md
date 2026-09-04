# JOOservices State Machine

[![CI](https://github.com/jooservices/state-machine/actions/workflows/ci.yml/badge.svg?branch=develop)](https://github.com/jooservices/state-machine/actions/workflows/ci.yml)
[![OpenSSF Scorecard](https://api.securityscorecards.dev/projects/github.com/jooservices/state-machine/badge)](https://securityscorecards.dev/viewer/?uri=github.com/jooservices/state-machine)
[![PHP Version](https://img.shields.io/badge/PHP-8.5%2B-blue.svg)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Version](https://img.shields.io/badge/version-4.0.0%20(unreleased)-orange.svg)](CHANGELOG.md)

The **JOOservices State Machine** is a PHP 8.5+ configuration-driven finite state machine for any PHP object — DTOs, POPOs, or framework models. Zero framework coupling. State is a string property on the subject.

Package name: `jooservices/state-machine`

Next public line: **`v4.0.0` (unreleased)** — no backward compatibility with the retired `v1.x` archive.

## Install

```bash
composer require jooservices/state-machine:^4.0@dev
```

> Until `v4.0.0` is tagged, require a `dev-develop` / path repository. Do not publish or tag without owner approval.
## Quick example

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

## What is supported today

- configuration-driven graphs validated at construction time
- `can()` / `apply()` / `getAvailableTransitions()`
- pluggable state accessors (property reflection or getter/setter)
- guards and before/after callbacks as class strings
- optional PSR-14 lifecycle events
- multiple independent graphs per subject (separate machine instances)
- pure PHP 8.5+ with no Laravel/Symfony runtime requirement

## Important current limitations

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
make install
make lint
make test
make ci
```

Or with Composer inside Docker / host PHP 8.5:

```bash
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

Approved Git flow summary:

- normal feature and fix work branches from `develop` and PRs back into `develop`
- release preparation uses `release/<version>` from `develop`, then PRs into `master`
- releases are tagged from `master` (**`v4.0.0` not tagged yet**)
- `master` merges back into `develop` after release or hotfix completion

## Community

- [Contributing](./CONTRIBUTING.md)
- [Security Policy](./SECURITY.md)
- [Code of Conduct](./CODE_OF_CONDUCT.md)
- [Support](./SUPPORT.md)

## GitHub Actions and Services

See [WORKFLOWS.md](./WORKFLOWS.md). Current coverage:

- `CI` + `CI Gate`: security, lint matrix, tests, 95% coverage
- `Commitlint` + `Semantic PR Title`
- `Release` (tag-driven — do not use until owner approves `v4.0.0`)
- OpenSSF Scorecard / optional Codacy / Fortify when secrets exist

## License

This project is licensed under the [MIT License](./LICENSE).
