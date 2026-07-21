# Setup

## Prerequisites

- PHP `>=8.5`
- Composer 2.x
- Git
- `gitleaks` if you want the pre-commit hook to pass locally

## Clone and install

```bash
git clone https://github.com/jooservices/state-machine.git
cd state-machine
composer install
```

Composer installs CaptainHook automatically through:

- `post-install-cmd`
- `post-update-cmd`

## Verify local commands

```bash
composer test
composer lint
composer lint:all
composer lint:fast
composer lint:fix
```

## CaptainHook notes

The repository uses `captainhook.json` for:

- commit message validation
- pre-commit checks
- pre-push checks

If hooks are missing, install them manually:

```bash
vendor/bin/captainhook install --force --skip-existing
```

## Secret-scanning prerequisite

The default CaptainHook flow includes:

- pre-commit: `gitleaks protect --staged --verbose --redact --config=.gitleaks.toml`
- pre-push: `gitleaks detect --verbose --redact --config=.gitleaks.toml || echo ...`

The blocking pre-commit secret scan runs:

```bash
gitleaks protect --staged --verbose --redact --config=.gitleaks.toml
```

Install `gitleaks` locally before relying on the default hook flow.

## Related documents

- [Testing](./04-testing.md)
- [Linting Standards](./03-linting-standards.md)
- [Secret Scanning](./10-secret-scanning.md)
