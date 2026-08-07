<?php

it('pins CI actions and service containers to immutable revisions', function () {
    $workflowDirectory = base_path('.github/workflows');
    $violations = [];

    foreach (glob($workflowDirectory . '/*.{yml,yaml}', GLOB_BRACE) as $workflow) {
        $contents = file_get_contents($workflow);

        preg_match_all('/^\s*uses:\s*([^\s#]+)/m', $contents, $actions);

        foreach ($actions[1] as $action) {
            if (! str_starts_with($action, './') && ! preg_match('/@[0-9a-f]{40}$/', $action)) {
                $violations[] = basename($workflow) . ": mutable action {$action}";
            }
        }

        preg_match_all('/^\s*image:\s*([^\s#]+)/m', $contents, $images);

        foreach ($images[1] as $image) {
            if (! preg_match('/@sha256:[0-9a-f]{64}$/', $image)) {
                $violations[] = basename($workflow) . ": mutable container {$image}";
            }
        }

        if (str_contains($contents, 'ubuntu-latest')) {
            $violations[] = basename($workflow) . ': floating runner image';
        }
    }

    expect($violations)->toBeEmpty()
        ->and(file_get_contents(base_path('.github/dependabot.yml')))
        ->toContain('package-ecosystem: "github-actions"');
});
