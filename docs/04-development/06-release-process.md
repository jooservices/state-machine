# Release Process

The repository's release automation is defined in `.github/workflows/release.yml`.

## Approved Git flow for releases

- normal feature and fix work: branch from `develop`, then open the PR back into `develop`
- release preparation: create `release/<version>` from the latest `develop`
- release metadata updates: keep changelog, README, version references, and release notes updates on `release/<version>` only
- release PR: open `release/<version>` into `master`
- production release: merge the release PR into `master`, then create tag `vX.Y.Z`
- sync back: merge `master` back into `develop` after the release is published

Branch roles:

- `master`: stable release branch; production-ready only; no direct normal-development commits
- `develop`: integration branch; source branch for `release/<version>`
- `release/<version>`: release-only stabilization and metadata branch; no unrelated feature work
- `hotfix/*`: urgent production fix branch from `master`; merge into `master`, then merge back into `develop`

## Trigger

Push a tag matching:

```text
v*.*.*
```

Example:

```bash
git tag v1.0.2
git push origin v1.0.2
```

Tag only after the approved release PR has been merged into `master`.

## Workflow stages

### 1. Validate

Runs:

```bash
composer validate --strict
composer lint:all
composer test
```

### 2. Create GitHub release

Generates a changelog and publishes a GitHub release.

### 3. Publish to Packagist

Posts to Packagist using `PACKAGIST_USERNAME` and `PACKAGIST_TOKEN` secrets.

## Practical maintainer checklist

Before tagging:

- confirm `develop` is current
- create `release/<version>` from the latest `develop`
- update changelog, README, version references, and release metadata on `release/<version>`
- open and approve the release PR from `release/<version>` into `master`
- merge the release PR into `master`
- confirm `composer test` passes locally
- confirm `composer lint:all` passes locally
- confirm `composer validate --strict` passes locally
- update any release-facing documentation that changed behavior or workflows

Tag and publish:

```bash
git checkout master
git pull origin master
git tag vX.Y.Z
git push origin vX.Y.Z
```

After the release workflow succeeds:

```bash
git checkout develop
git pull origin develop
git merge origin/master
git push origin develop
```

Do not update changelog, README, version references, or other release metadata directly on `master`.

## Related documents

- [CI/CD](./05-ci-cd.md)
- [Testing](./04-testing.md)
