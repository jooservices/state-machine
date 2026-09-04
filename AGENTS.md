# jooservices/state-machine

This file adds project-only rules.

- PHP `^8.5`; runtime: `psr/event-dispatcher ^1.0` only — zero framework coupling
- First public line: **`v4.0.0`** (not released yet) — no backward compatibility with the retired `v1.x` line
- All PHP tooling via Docker (`php:8.5-cli-bookworm`, image `jooservices/state-machine:php85`)
- CI on GitHub-hosted `ubuntu-latest`
- Lints at **max** with **no ignore**: Pint `per`, PHPCS, PHPStan max + strict rules, PHPMD, PHP-CS-Fixer
- Coverage floor **95%** statement coverage
- Guards/callbacks are class-strings validated at config time (`new $class()` — no container)
- Accessors fail loud on missing write targets (`StateAccessException`)
- Branch model: `develop` for integration, `master` for production, tags from `master`
