# GitHub Actions workflow flow

This document describes the workflows currently defined in
`.github/workflows/`. Jobs run on GitHub-hosted `ubuntu-latest` runners; PHP
CI and release jobs use `shivammathur/setup-php` for PHP 8.5. Branch protection
on `master`/`develop` requires the pull-request checks before merge.

## Pull-request gate (`ci.yml`)

**Trigger:** pull requests and pushes targeting `master` or `develop`.

Jobs (ordered by dependency):

1. **Security Checks** — `composer validate --strict`, `composer audit`
2. **Lint matrix** — Pint, PHPCS, PHPStan, PHPMD, PHP-CS-Fixer
3. **Tests & Coverage** — PHPUnit + 95% statement coverage floor
4. **CI Gate** — final required job after tests succeed
5. **SonarQube** (optional) — when `SONAR_TOKEN` is present

Related PR checks: **Commitlint**, **Semantic PR Title**, and **PR Labeler**;
`ci.yml` also runs the optional, non-blocking **Dependency Review** job on PRs.

## Other workflows

| Workflow | Triggers | Role |
| --- | --- | --- |
| `semantic-pr.yml` | PR opened, edited, or synchronized | Validates the PR title |
| `commitlint.yml` | PR opened, edited, synchronized, or reopened | Validates PR commit messages |
| `pr-labeler.yml` | PR opened, synchronized, or reopened | Applies labels based on changed paths |
| `scorecard.yml` | Push to `develop`; weekly Monday at 00:00 UTC (`0 0 * * 1`); manual `workflow_dispatch` | Runs OpenSSF Scorecard and uploads SARIF results |
| `secret-scanning.yml` | Push to `master`/`develop`; any PR; weekly Sunday at 00:00 UTC (`0 0 * * 0`); manual `workflow_dispatch` | Currently reports that Gitleaks is disabled until `GITLEAKS_LICENSE` is configured; the Gitleaks job is commented out |
| `codacy.yml` | Push or PR to `master`; weekly Tuesday at 22:23 UTC (`23 22 * * 2`) | Runs Codacy Analysis CLI and uploads SARIF results |
| `fortify.yml` | Push or PR to `master`; weekly Wednesday at 04:32 UTC (`32 4 * * 3`); manual `workflow_dispatch` | Runs Fortify scans when credentials are configured; otherwise reports a successful skip |
| `release.yml` | Push of a tag matching `v*.*.*` | Validates and creates a GitHub Release; updates Packagist only for tags without a hyphen |

## Runtime truth

- Local gate: `make ci` (Docker) or `composer ci`
- Never invent undocumented workflow or check names when locking branch protection
