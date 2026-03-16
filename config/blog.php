<?php

return [
    'markdown' => [
        'posts_path' => resource_path('markdown/posts'),
        'tools_path' => resource_path('markdown/tools'),
    ],
    'preview_base_url' => env('BLOG_PREVIEW_BASE_URL'),
    'screenshot' => [
        'node_binary' => env('NODE_BINARY', 'node'),
        'npm_binary' => env('NPM_BINARY', 'npm'),
    ],
];
