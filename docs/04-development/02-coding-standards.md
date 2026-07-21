# Coding Standards

The **JOOservices State Machine** uses layered quality tooling rather than a single formatter.

## Source of truth

- `pint.json`
- `.php-cs-fixer.dist.php`
- `phpcs.xml`
- `phpmd.xml`
- `phpstan.neon`
- Composer scripts in `composer.json`

If these files and supporting documentation ever disagree, treat the configuration files and `composer.json` scripts as authoritative, then update the weaker document.

## Tool responsibilities

### Pint

Primary formatting authority.

### PHP-CS-Fixer

Deliberately narrow PHPDoc cleanup complement to Pint.

### PHPCS

Structural sniffs such as:

- side effects in declaration files
- class declaration rules
- method naming rules outside tests
- forbidden and deprecated function checks

### PHPStan

Maximum level analysis with strict rules and PHPUnit integration.

### PHPMD

Complexity and design-smell checks for `src/`.

## Commands

```bash
composer lint
composer lint:all
composer lint:fix
composer lint:fast
composer test
composer test:coverage
composer check
composer ci
```

## Usage rule

- Use `composer lint:fast` for the fast local gate.
- Use `composer lint` or `composer lint:all` when validating the full non-mutating quality stack.
- Use `composer lint:fix` instead of inventing alternative fixer commands.
- Use `composer check` for the combined lint-and-test gate.
- Use `composer ci` for the CI-style lint-and-coverage gate.

## Important correction

The repository does not define `composer fix`. The correct fixer command is:

```bash
composer lint:fix
```

## Related documents

- [Code Quality](./04-code-quality.md)
- [Linting Standards](./03-linting-standards.md)
- [Testing](./04-testing.md)
