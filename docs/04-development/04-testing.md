# Testing

## Test framework

The repository uses PHPUnit, configured in `phpunit.xml`.

## Suite layout

Current structure:

- `tests/TestCase.php`: shared PHPUnit base test case with Faker setup
- `tests/Fixtures`: DTOs, enums, and helper classes reused across tests
- `tests/Unit`: isolated class and module coverage, mirrored by source module where practical
- `tests/Integration`: end-to-end package behavior across mapper, hydrator, engine, normalizer, validation, and schema boundaries
- `tests/Benchmark`: benchmark-only cases, not part of the PHPUnit suites

This package uses `tests/Integration` for cross-module flow tests. Do not introduce `tests/Feature` unless the repository adds an actual feature-test suite to `phpunit.xml`.

## Main commands

```bash
composer test
composer test:coverage
composer bench
composer bench:quick
composer bench:baseline
composer bench:compare
```

## What `phpunit.xml` currently enforces

- strict output and warning handling
- separate unit and integration suites
- coverage reports written to `coverage/` and `coverage.xml`
- source coverage limited to all PHP files under `src/`

## Test doubles and mocks

Prefer real DTO fixtures, small in-test stub classes, and PHPUnit's built-in test double APIs. The repository does not currently depend on Mockery; do not add a mocking library unless the real code need justifies the dependency, docs, and CI impact.

## CI behavior

`ci.yml` runs:

- `composer test:coverage`
- a shell check that fails if statement coverage drops below 95%

The repository standard is 95% minimum statement coverage. If future coverage drops below this threshold, add focused unit or integration tests instead of lowering the gate.

The current workflow targets PHP 8.5 only. It is not a multi-version test matrix.

## Benchmarks

Benchmark cases live under `tests/Benchmark` and are executed with `phpbench`, not PHPUnit.

Use:

```bash
composer bench
```

Use `composer bench:quick` for a short local smoke check before and after a performance-sensitive change. Use `composer bench:baseline` before the change to write `build/phpbench/baseline.xml`, then use `composer bench:compare` after the change to compare the fresh run with that baseline.

Current benchmark coverage now includes hydration, normalization, and collection-oriented scenarios. These runs are for local performance comparison and regression tracking; they are not part of the default PHPUnit suites or the default pull request gate.

## Related documents

- [Setup](./01-setup.md)
- [Code Quality](./04-code-quality.md)
- [CI/CD](./05-ci-cd.md)
