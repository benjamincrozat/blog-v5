<?php

return [
    'legacy_migrations_without_down' => [
        'database/migrations/2025_08_23_184026_add_column_to_posts_table.php',
        'database/migrations/2025_08_25_231033_create_reports_table.php',
        'database/migrations/2025_08_26_162015_create_revisions_table.php',
        'database/migrations/2025_08_31_120000_add_completed_at_to_revisions_table.php',
        'database/migrations/2025_08_31_121000_add_completed_at_to_reports_table.php',
    ],
    'internal_anchor_without_wire' => [
        'resources/views/posts/show.blade.php::{{ route(\'feeds.main\') }}',
        'resources/views/components/app.blade.php::{{ route(\'leave-impersonation\') }}',
    ],
];
