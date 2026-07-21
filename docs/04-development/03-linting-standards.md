# Linting Standards

## Command map

```bash
composer lint:fast     # Pint + PHPCS + PHPStan
composer lint          # lint:fast + PHPMD + PHP-CS-Fixer dry-run
composer lint:all      # alias for lint
composer lint:fix      # Pint fix + PHP-CS-Fixer fix
composer check         # lint:all + test
composer ci            # lint:all + test:coverage
composer instructions:verify
composer lint:pint
composer lint:pint:fix
composer lint:phpcs
composer lint:phpstan
composer lint:phpmd
composer lint:cs
composer lint:cs:fix
```

## Command execution order

For a full local quality pass, the repository command flow is:

1. `composer lint`
2. `composer test`

Within `composer lint`, the execution order is:

1. `composer lint:pint`
2. `composer lint:phpcs`
3. `composer lint:phpstan`
4. `composer lint:phpmd`
5. `composer lint:cs`

For the previous quick subset, use `composer lint:fast`.

## Tool responsibility order

Read the tools in this conceptual order:

1. Pint
2. PHP-CS-Fixer
3. PHPCS
4. PHPStan
5. PHPMD

That responsibility split reflects the repository's intent:

- Pint owns broad style decisions.
- PHP-CS-Fixer handles a small non-overlapping PHPDoc delta.
- PHPCS checks structure.
- PHPStan and PHPMD check correctness and maintainability.
- No new PHPStan `ignoreErrors` entries should be added without narrow scope and clear reason.
- No new PHPMD global exclusions should be added unless real DTO package code proves they are necessary.
- If checker failures represent real code smell, fix the code instead of suppressing the failure.
- Any suppression should be reported in final WHAT/WHY/HOW notes.

## Source-of-truth rule

If this page, `captainhook.json`, CI workflows, or `composer.json` ever conflict, prefer:

1. `composer.json` for command names
2. `captainhook.json` for local hook behavior
3. `.github/workflows/*.yml` for CI behavior

Then update this document to match.

## Hook behavior

The default CaptainHook flow currently runs:

### Pre-commit

- PHP linting
- `gitleaks protect --staged`
- `composer lint:pint`
- `composer lint:phpcs`
- `composer lint:phpstan`
- `composer lint:phpmd`
- `composer lint:cs`

### Pre-push

- `gitleaks detect ... || echo ...`
- `composer test`

## CI behavior

The CI workflow runs the non-mutating lint commands as separate matrix jobs:

- `lint:pint`
- `lint:phpcs`
- `lint:phpstan`
- `lint:phpmd`
- `lint:cs`
- `instructions:verify`

## Related documents

- [Coding Standards](./02-coding-standards.md)
- [Code Quality](./04-code-quality.md)
- [CI/CD](./05-ci-cd.md)
