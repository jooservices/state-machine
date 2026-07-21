# JOOservices State Machine

[![CI](https://github.com/jooservices/state-machine/actions/workflows/ci.yml/badge.svg?branch=develop)](https://github.com/jooservices/state-machine/actions/workflows/ci.yml)
[![OpenSSF Scorecard](https://api.securityscorecards.dev/projects/github.com/jooservices/state-machine/badge)](https://securityscorecards.dev/viewer/?uri=github.com/jooservices/state-machine)
[![PHP Version](https://img.shields.io/badge/PHP-8.5%2B-blue.svg)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/jooservices/state-machine)](https://packagist.org/packages/jooservices/state-machine)

The **JOOservices State Machine** is a PHP 8.5+ configuration-driven finite state machine for any PHP object — DTOs, POPOs, or framework models. Zero framework coupling. State is a string property on the subject.

Package name: `jooservices/state-machine`

Latest stable release: `v1.0.0`

## Install

```bash
composer require jooservices/state-machine
```

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
- event dispatcher is optional; consumers bring their own PSR-14 implementation
- no built-in persistence, Eloquent casts, or service providers
- missing properties/methods are handled conservatively by accessors (null / no-op)

## Documentation

Start with:

- [Documentation Hub](./docs/README.md)
- [Changelog](./CHANGELOG.md)
- [AI Skills Usage Guide](./ai/skills/USAGE.md)
- phpDocumentor config: [`phpdoc.dist.xml`](./phpdoc.dist.xml)
- [Installation](./docs/01-getting-started/01-installation.md)
- [Quick Start](./docs/01-getting-started/02-quick-start.md)
- [Risks, Legacy, and Gaps](./docs/05-maintenance/01-risks-legacy-and-gaps.md)

## AI Support

This repository includes an AI skill pack for agents working in Cursor, Claude Code, VS Code, JetBrains, and Antigravity.

Start with:

- [`AGENTS.md`](./AGENTS.md)
- [`CLAUDE.md`](./CLAUDE.md)
- [AI Skills Map](./ai/skills/README.md)
- [AI Skills Usage Guide](./ai/skills/USAGE.md)

The canonical skill source lives in [`.github/skills/`](./.github/skills/), with adapter layers for each supported AI environment.

## Development

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
- [AI Skills](./docs/04-development/08-ai-skills.md)

Approved Git flow summary:

- normal feature and fix work branches from `develop` and PRs back into `develop`
- release preparation uses `release/<version>` from `develop`, then PRs into `master`
- releases are tagged from `master`
- `master` merges back into `develop` after release or hotfix completion

## Community

- [Contributing](./CONTRIBUTING.md)
- [Security Policy](./SECURITY.md)
- [Code of Conduct](./CODE_OF_CONDUCT.md)

## GitHub Actions and Services

Current GitHub Actions coverage:

- `CI`: security checks, linting, tests, 95% minimum statement coverage, coverage upload, and optional SonarQube Cloud analysis when `SONAR_TOKEN` is configured
- `Release`: validate tags, create GitHub releases, trigger Packagist update
- `PR Labeler`: apply labels to pull requests
- `Semantic PR Title`: enforce pull request title format
- `OpenSSF Scorecard`: publish scorecard results as SARIF
- `Secret Scanning`: workflow file exists, but the `gitleaks` job may be disabled depending on repository settings

External services currently used by workflows:

- `Codecov` for coverage upload in [`ci.yml`](./.github/workflows/ci.yml)
- `Packagist` update webhook in [`release.yml`](./.github/workflows/release.yml)
- `GitHub Releases` and `GitHub Discussions` in [`release.yml`](./.github/workflows/release.yml)
- `OpenSSF Scorecard` in [`scorecard.yml`](./.github/workflows/scorecard.yml)

## License

This project is licensed under the [MIT License](./LICENSE).
