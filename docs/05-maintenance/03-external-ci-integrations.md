# External CI Integrations

This repository enforces quality through GitHub Actions workflows under `.github/workflows/` and the branch ruleset named `develop & master`.

Optional third-party GitHub Apps may still report statuses on pull requests even when they are not configured in this repository.

## Required GitHub Actions checks

The active ruleset requires these GitHub Actions contexts:

- `GitGuardian Security Checks`
- `Security Checks`
- `Lint - Pint`
- `Lint - PHPCS`
- `Lint - PHPStan`
- `Lint - PHPMD`
- `Lint - PHP-CS-Fixer`
- `Tests & Coverage`

`Lint - AI Instructions`, SonarCloud, Codacy, OpenSSF Scorecard, and Fortify workflows may run on some events, but they are not part of the required ruleset today.

## Scrutinizer CI

Scrutinizer CI is not configured in this repository. There is no `.scrutinizer.yml`, workflow file, or ruleset entry for it.

When the Scrutinizer GitHub App is installed on the organization, it can still post a failing `Scrutinizer` status on pull requests even though repository CI is green. Recent failures have been platform-side (`Tests: errored`, worker scheduling issues) rather than code defects in this package.

To remove Scrutinizer statuses from future pull requests:

1. Open the organization GitHub App installations at `https://github.com/organizations/jooservices/settings/installations`.
2. Select **Scrutinizer CI**.
3. Choose **Configure**, remove access to `jooservices/state-machine`, or uninstall the app entirely if it is no longer needed.

After removal, only repository-defined GitHub Actions checks remain visible on pull requests targeting `develop` or `master`.

## Codacy SARIF upload behavior

The Codacy workflow normalizes SARIF output and uploads it to GitHub Code Scanning. The normalize and upload steps intentionally use `continue-on-error: true` because GitHub SARIF ingestion can fail on duplicate automation IDs or repository permission edge cases while the underlying Codacy scan still completes successfully.
