# Code Quality

## Purpose

Document the reusable code-quality configuration files, Composer commands, conflict rules, local gates, CI expectations, and coverage standard for the **JOOservices State Machine**.

## Scope

This page covers repository tooling only. Runtime design decisions are covered by architecture and user-guide documents.

## Status

Authoritative human-readable reference for code-quality config usage. If this page conflicts with real configuration, update this page to match the repository files.

## Summary

The repository uses one reusable config file per quality tool. Tools must not be combined into a single shared config.

## Rules / Policy

### Config Files

| Tool | Config file | Responsibility |
| --- | --- | --- |
| Pint | `pint.json` | Primary formatting authority |
| PHPStan | `phpstan.neon` | Static analysis at `level: max` with strict rules and PHPUnit integration |
| PHPCS | `phpcs.xml` | Structural and code-quality checks that complement Pint |
| PHPMD | `phpmd.xml` | Complexity and design-smell checks for `src/` |
| PHP-CS-Fixer | `.php-cs-fixer.dist.php` | Narrow non-conflicting PHPDoc cleanup |
| PHPUnit | `phpunit.xml` | Test suites, strict test behavior, and coverage reports |
| CaptainHook | `captainhook.json` | Local git hooks and Conventional Commit validation |

### Conflict Resolution

- Pint is the formatting authority.
- If Pint conflicts with PHPCS or PHP-CS-Fixer, Pint wins.
- PHPCS should focus on structural and code-quality checks and must not duplicate Pint formatting rules.
- PHP-CS-Fixer should stay limited to narrow non-conflicting cleanup, especially PHPDoc cleanup.
- PHPStan must run at the strictest feasible level. This repository currently uses `level: max`.
- No new PHPStan `ignoreErrors` should be added without narrow scope and a clear reason.
- Laravel applications should use Larastan where applicable. This repository is a framework-agnostic package and does not currently use Larastan.
- PHPMD should detect complexity and design smells but must not block valid DTO, model, or factory patterns with unrealistic thresholds.
- No new PHPMD global exclusions should be added unless proven necessary by real DTO package code.
- If checker failures represent real code smell, fix the code instead of suppressing the failure.
- Any suppression should be reported in final WHAT/WHY/HOW notes.
- PHPUnit must fail on warnings, notices, deprecations, risky tests, incomplete tests, empty test suites, and unexpected output.

### Composer Command Map

| Command | Runs |
| --- | --- |
| `composer lint:fast` | Pint, PHPCS, and PHPStan |
| `composer lint` | `composer lint:fast`, PHPMD, and PHP-CS-Fixer dry run |
| `composer lint:all` | Alias for `composer lint` |
| `composer lint:fix` | Pint fixer and PHP-CS-Fixer |
| `composer instructions:verify` | AI instruction sync checks |
| `composer test` | PHPUnit |
| `composer test:coverage` | PHPUnit with HTML and Clover coverage reports |
| `composer bench` | Default phpbench benchmark run |
| `composer bench:quick` | Short local phpbench smoke run |
| `composer bench:baseline` | Writes `build/phpbench/baseline.xml` |
| `composer bench:compare` | Compares a fresh run against `build/phpbench/baseline.xml` |
| `composer check` | Full lint stack and tests |
| `composer ci` | Full lint stack and coverage tests |

Do not invent alternative command names such as `composer fix`. Use `composer lint:fix`.

### Local Gate Before Commit Or PR

Before committing or opening a PR, run the relevant repository-defined checks and resolve all errors, warnings, notices, risky tests, deprecations, and unrelated changes.

Minimum local gate for ordinary changes:

```bash
composer lint:all
composer test
```

Use `composer check` for a combined local gate. Use `composer ci` or `composer test:coverage` when coverage impact matters.

### CI Expectation

CI runs:

- Composer validation and `composer audit --locked --abandoned=ignore`
- lint matrix for Pint, PHPCS, PHPStan, PHPMD, and PHP-CS-Fixer
- AI instruction sync verification
- coverage tests
- 95% minimum statement coverage threshold check
- optional dependency review on pull requests
- optional SonarQube Cloud analysis when configured

The Composer audit gate is intentionally scoped to locked non-dev dependencies so runtime advisories and abandoned runtime packages still fail CI, while abandoned dev-only tooling does not block normal PR flow.

CI must pass, but CI does not replace local responsibility to run and verify the relevant checks before commit or PR.

### Coverage Expectation

The repository standard is at least 95% statement coverage, enforced by `.github/workflows/ci.yml`.

If coverage falls below 95%, add focused unit and integration coverage with behavior changes rather than lowering quality-tool expectations.

## Details

- `pint.json` uses the Laravel preset and repository-specific formatting rules.
- `.php-cs-fixer.dist.php` intentionally contains only a small PHPDoc cleanup set.
- `phpcs.xml` avoids broad formatting rules and checks structural concerns.
- `phpstan.neon` includes strict rules and PHPUnit integration.
- `phpmd.xml` uses calibrated thresholds so maintainability checks do not reject valid DTO, factory, or mapper shapes.
- `tools/verify-ai-instructions-sync.php` checks important AI instruction invariants across primary adapter files.
- `phpunit.xml` enables strict warning, notice, deprecation, risky-test, incomplete-test, and output handling.
- `captainhook.json` validates Conventional Commits and runs local lint/test gates through hooks.

## Examples

Full pre-PR validation:

```bash
composer lint:all
composer test
composer check
```

Coverage-sensitive validation:

```bash
composer lint:all
composer test:coverage
```

## Checklist

- Each tool has one reusable config file.
- Pint remains the formatting authority.
- PHPCS and PHP-CS-Fixer do not duplicate Pint formatting responsibility.
- PHPStan remains at the strictest feasible level.
- PHPStan and PHPMD suppressions remain narrow, justified, and reported.
- PHPUnit strict failure behavior remains enabled.
- Coverage remains at or above the 95% statement threshold.
- Composer commands match `composer.json`.
- Local and CI gates are both described truthfully.

## Related Documents

- [Coding Standards](./02-coding-standards.md)
- [Linting Standards](./03-linting-standards.md)
- [Testing](./04-testing.md)
- [CI/CD](./05-ci-cd.md)
- [Application Architecture Standards](../00-architecture/06-application-architecture-standards.md)
