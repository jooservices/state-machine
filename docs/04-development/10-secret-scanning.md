# Secret Scanning

## Local hook enforcement

The current repository enforces secret scanning locally through CaptainHook.

### Pre-commit

Runs:

```bash
gitleaks protect --staged --verbose --redact --config=.gitleaks.toml
```

This check is blocking. If `gitleaks` is not installed, the hook will fail.

### Pre-push

Runs:

```bash
if ! command -v gitleaks >/dev/null 2>&1; then
	echo 'Note: install gitleaks: brew install gitleaks'
	exit 0
fi

default_ref=$(git symbolic-ref --quiet refs/remotes/origin/HEAD 2>/dev/null | sed 's@^refs/remotes/@@')
gitleaks detect --verbose --redact --config=.gitleaks.toml --log-opts="${default_ref:-origin/develop}..HEAD"
```

This hook is non-blocking only when `gitleaks` is not installed. If `gitleaks` is available but the scan fails, the push is blocked.

## CI status

The repository contains `.github/workflows/secret-scanning.yml`, but the actual `gitleaks` job is commented out. Secret scanning is therefore not currently an active GitHub Actions enforcement layer.

## Configuration

The repository includes `.gitleaks.toml`, which:

- extends the default Gitleaks rules
- allowlists specific fixture and placeholder patterns
- ignores some generated and dependency files

## Recommendation for contributors

Install `gitleaks` locally before relying on the default hook flow:

```bash
brew install gitleaks
```

Then verify it is available:

```bash
gitleaks version
```

## Related documents

- [Setup](./01-setup.md)
- [CI/CD](./05-ci-cd.md)
