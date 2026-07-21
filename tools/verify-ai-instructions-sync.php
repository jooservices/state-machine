<?php

declare(strict_types=1);

/**
 * Verifies that critical AI-facing policy invariants remain visible across the
 * repository's instruction adapters. Keep this script focused on policy drift,
 * not prose style.
 */
$root = dirname(__DIR__);

$files = [
    'AGENTS.md',
    'CLAUDE.md',
    '.claude/commands/quality-check.md',
    'ai/skills/README.md',
    '.cursor/rules/00-repo-quality-foundation.mdc',
    '.cursor/rules/coverage-and-lint-guard.mdc',
    '.github/copilot-instructions.md',
    '.github/instructions/repo-quality.instructions.md',
    '.github/skills/php-package-development/SKILL.md',
    '.github/skills/repo-quality-foundation/SKILL.md',
    '.github/skills/coverage-and-lint-guard/SKILL.md',
    '.github/skills/runtime-compatibility-guard/SKILL.md',
    'jetbrains/prompts/quality-check.md',
];

$missingFiles = [];
$fileContents = [];
$corpus = '';
$failures = [];

foreach ($files as $file) {
    $path = $root.'/'.$file;

    if (! is_file($path)) {
        $missingFiles[] = $file;

        continue;
    }

    $content = file_get_contents($path);
    if ($content === false) {
        $failures[] = 'Unreadable instruction file: '.$file.' ('.$path.')';

        continue;
    }

    $fileContents[$file] = $content;

    $corpus .= "\n\n--- ".$file." ---\n";
    $corpus .= $content;
}

$globalRequiredPatterns = [
    'stop-and-ask behavior' => '/stop and ask/i',
    'no silent assumptions' => '/Do not silently infer, assume, or decide/i',
    'Pint formatting authority' => '/Pint (?:wins|as the primary formatting authority|is the (?:primary )?formatting authority)/i',
    'PHPStan max or strictest feasible level' => '/PHPStan.*(?:level: max|strictest feasible|max)/is',
    'PHPMD maintainability checks' => '/PHPMD.*maintainability/is',
    'PHPCS structural-only responsibility' => '/PHPCS.*structural/is',
    '95% statement coverage' => '/95%.*statement coverage/is',
    'develop and master Git flow' => '/develop.*master/is',
    'no direct commits to protected branches' => '/Never commit directly to.*develop.*master/is',
    'release branch flow' => '/release\/<version>.*develop.*master/is',
    'runtime truth guards' => '/new \$class\(\)|Guard and callback class strings|no built-in container resolution/is',
    'optional event dispatcher truth' => '/(?:event dispatcher is optional|optional PSR-14|dispatcher itself is optional)/i',
    'suppression disclosure policy' => '/(?:No new PHPStan `ignoreErrors`|analysis suppressions|avoid broad suppressions)/i',
];

$requiredPatternsByFile = [
    'AGENTS.md' => [
        'lint:fast command map entry' => '/`composer lint:fast`/',
    ],
    '.github/skills/repo-quality-foundation/SKILL.md' => [
        'lint:fast command map entry' => '/`composer lint:fast`/',
        'bench:quick command map entry' => '/`composer bench:quick`/',
        'bench:baseline command map entry' => '/`composer bench:baseline`/',
        'bench:compare command map entry' => '/`composer bench:compare`/',
    ],
    '.github/skills/php-package-development/SKILL.md' => [
        'fast gate maps to lint:fast' => '/Fast local gate:\s*`composer lint:fast`/i',
        'full gate maps to lint' => '/Full local gate:\s*`composer lint`/i',
    ],
    '.claude/commands/quality-check.md' => [
        '95% coverage language' => '/95%\s*(?:coverage gate|statement coverage)/i',
    ],
    'jetbrains/prompts/quality-check.md' => [
        '95% coverage language' => '/95%\s*(?:coverage gate|statement coverage)/i',
    ],
];

$forbiddenPatterns = [
    'stale 90% coverage gate' => '/90% coverage gate/i',
];

foreach ($requiredPatternsByFile as $file => $patterns) {
    if (! array_key_exists($file, $fileContents)) {
        continue;
    }

    foreach ($patterns as $label => $pattern) {
        if (preg_match($pattern, $fileContents[$file]) !== 1) {
            $failures[] = 'Missing invariant in '.$file.': '.$label;
        }
    }
}

foreach ($globalRequiredPatterns as $label => $pattern) {
    if (preg_match($pattern, $corpus) !== 1) {
        $failures[] = 'Missing invariant: '.$label;
    }
}

foreach ($forbiddenPatterns as $label => $pattern) {
    if (preg_match($pattern, $corpus) === 1) {
        $failures[] = 'Forbidden stale policy found: '.$label;
    }
}

foreach ($missingFiles as $file) {
    $failures[] = 'Missing instruction file: '.$file;
}

if ($failures !== []) {
    fwrite(STDERR, "AI instruction sync verification failed:\n");

    foreach ($failures as $failure) {
        fwrite(STDERR, '- '.$failure."\n");
    }

    exit(1);
}

echo "AI instruction sync verification passed.\n";
