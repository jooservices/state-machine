# CI/CD

## Active workflows

The repository currently includes:

- `ci.yml`
- `codacy.yml`
- `fortify.yml`
- `pr-labeler.yml`
- `release.yml`
- `scorecard.yml`
- `secret-scanning.yml`
- `semantic-pr.yml`

## `ci.yml`

Triggers:

- push to `master` or `develop`
- pull requests targeting `master` or `develop`

In the approved Git flow, that means:

- feature and normal fix PRs are validated when they target `develop`
- release and hotfix PRs are validated when they target `master`

Jobs:

- `security`: `composer validate --strict`, then `composer audit --locked --abandoned=ignore`
- `lint`: matrix over Pint, PHPCS, PHPStan, PHPMD, PHP-CS-Fixer dry-run, and AI instruction sync verification
- `dependency-review`: pull-request only, non-blocking
- `tests`: coverage run plus the 95% minimum statement coverage threshold check
- `sonarcloud`: optional SonarQube Cloud analysis after a successful test run when `SONAR_TOKEN` is configured in the job environment and the event is allowed

The security job audits the lock file because this package has no third-party runtime dependencies beyond PHP. That keeps PR and release validation focused on known advisories in locked tooling while avoiding Composer 2.10 failures from `composer audit --no-dev --locked` when no production packages are installed.

Codecov upload is best-effort. When `CODECOV_TOKEN` is unavailable on protected branches, the workflow keeps `fail_ci_if_error: false` so coverage threshold enforcement in the test job remains the authoritative gate.

CI enforces the repository standard of 95% minimum statement coverage.

## `codacy.yml`

Runs on pull requests and pushes targeting `master`, plus a weekly schedule. It uploads Codacy SARIF results to GitHub Code Scanning. Normalize and upload steps are non-blocking so transient SARIF ingestion issues do not block release PRs.

## `fortify.yml`

Optional Fortify scanning workflow. When Fortify credentials are not configured, a no-op `fortify-skipped` job reports the skipped state so the workflow still completes successfully.

## External optional statuses

Third-party apps such as Scrutinizer CI are not configured in this repository and are not required by the branch ruleset. See [External CI Integrations](../05-maintenance/03-external-ci-integrations.md) for removal steps when an optional app posts failing statuses despite green GitHub Actions checks.

## `pr-labeler.yml`

Uses `.github/labeler.yml` to apply labels such as:

- `documentation`
- `source`
- `tests`
- `dependencies`
- `ci/cd`
- `configuration`

## `semantic-pr.yml`

Checks pull request titles against the configured Conventional Commit types and requires the subject to start with an uppercase letter.

## `scorecard.yml`

Runs OpenSSF Scorecard on:

- pushes to `master`
- a weekly schedule
- manual dispatch

## `secret-scanning.yml`

The workflow file exists, but its `gitleaks` job is currently commented out. A no-op `secret-scanning-disabled` job keeps push and schedule triggers green until Gitleaks is re-enabled.

## `release.yml`

Triggers on tags matching `v*.*.*`.

Jobs:

- `validate`: runs `composer validate --strict`, `composer audit --locked --abandoned=ignore`, `composer lint:all`, and `composer test` with `pcov` enabled for PHPUnit
- `release`: creates a GitHub release using GitHub's generated release notes
- `publish`: posts to Packagist using repository secrets

The release workflow does not replace the approved branch flow. Create `release/<version>` from `develop`, merge that release PR into `master`, then tag from `master`.

## Related documents

- [Code Quality](./04-code-quality.md)
- [Release Process](./06-release-process.md)
- [Testing](./04-testing.md)
- [Secret Scanning](./10-secret-scanning.md)
