# GitHub Actions workflow flow

This document describes the workflows currently defined in
`.github/workflows/`. Jobs run on GitHub-hosted `ubuntu-latest` runners with
`shivammathur/setup-php` for PHP 8.5. Branch protection on `master`/`develop`
requires the pull-request checks before merge.

## Pull-request gate (`ci.yml`)

**Trigger:** pull requests and pushes targeting `master` or `develop`.

Jobs (ordered by dependency):

1. **Security Checks** — `composer validate --strict`, `composer audit`
2. **Lint matrix** — Pint, PHPCS, PHPStan, PHPMD, PHP-CS-Fixer
3. **Tests & Coverage** — PHPUnit + 95% statement coverage floor
4. **CI Gate** — final required job after tests succeed
5. **SonarQube** (optional) — when `SONAR_TOKEN` is present

Also on PRs: **Commitlint**, **Semantic PR Title**, **PR Labeler**, **Dependency Review**.

## Other workflows

| Workflow | Role |
| --- | --- |
| `semantic-pr.yml` | Conventional PR title |
| `commitlint.yml` | Conventional commit messages on the PR |
| `pr-labeler.yml` | Path-based labels |
| `scorecard.yml` | OpenSSF Scorecard |
| `secret-scanning.yml` | Extra secret scan job (GitHub native Secret Scanning is also on) |
| `codacy.yml` / `fortify.yml` | Optional vendor scans when secrets exist |
| `release.yml` | Tag `v*.*.*` → validate → GitHub Release → Packagist update |

## Runtime truth

- Local gate: `make ci` (Docker) or `composer ci`
- Never invent undocumented workflow or check names when locking branch protection
