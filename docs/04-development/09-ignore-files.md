# Ignore Files

The **JOOservices State Machine** currently ships these repository-level ignore files:

- `.gitignore`
- `.gitattributes`
- `.editorconfig`
- `.dockerignore`
- `.cursorignore`
- `.gitleaks.toml`

## What each file controls

### `.gitignore`

Excludes local-only files such as:

- `vendor/`
- coverage output
- tool caches
- environment files
- editor metadata

### `.gitattributes`

Defines:

- text normalization
- binary file handling
- export-ignore rules for release archives

Notably, it excludes `docs/`, `tests/`, and several dev-only files from export.

### `.editorconfig`

Provides editor defaults for:

- UTF-8
- LF line endings
- 4-space indentation for PHP
- 2-space indentation for JSON, YAML, and XML

### `.dockerignore`

Shrinks Docker build context and excludes:

- VCS metadata
- dependencies
- most documentation files except `README.md`
- CI files

### `.cursorignore`

Reduces AI/editor indexing noise for generated files, caches, dependencies, and large artifacts.

### `.gitleaks.toml`

Configures local secret scanning.

## Maintenance guidance

Update these files whenever you add:

- a new cache directory
- a generated artifact
- a new documentation build output
- a new security or packaging tool

## Related documents

- [Secret Scanning](./10-secret-scanning.md)
- [Setup](./01-setup.md)
