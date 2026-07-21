---
mode: agent
description: Check release readiness for tag-driven GitHub release and Packagist publish
tools: ["codebase", "search", "runCommands"]
---

Apply:

- [.github/skills/repo-quality-foundation/SKILL.md](/Users/vietvu/Sites/JOOservices/dto/.github/skills/repo-quality-foundation/SKILL.md)
- [.github/skills/review-and-risk-assessment/SKILL.md](/Users/vietvu/Sites/JOOservices/dto/.github/skills/review-and-risk-assessment/SKILL.md)
- [.github/skills/commit-and-pr-authoring/SKILL.md](/Users/vietvu/Sites/JOOservices/dto/.github/skills/commit-and-pr-authoring/SKILL.md)
- [.github/skills/dependency-and-versioning-policy/SKILL.md](/Users/vietvu/Sites/JOOservices/dto/.github/skills/dependency-and-versioning-policy/SKILL.md)
- [.github/skills/release-management/SKILL.md](/Users/vietvu/Sites/JOOservices/dto/.github/skills/release-management/SKILL.md)
- [.github/skills/ci-hooks-maintenance/SKILL.md](/Users/vietvu/Sites/JOOservices/dto/.github/skills/ci-hooks-maintenance/SKILL.md)

Release target:

${input}

Use `.github/workflows/release.yml` as the workflow source of truth.
Apply the approved Git flow explicitly:

- feature and fix work merges into `develop`
- release preparation uses `release/<version>` from `develop`
- release PR targets `master`
- tag from `master` after the release PR merge
- merge `master` back into `develop` after release
