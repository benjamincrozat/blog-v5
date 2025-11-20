<?php

namespace App\Actions;

use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Models\Revision;
use App\Notifications\NewRevision;
use OpenAI\Laravel\Facades\OpenAI;

class RevisePost
{
    public function revise(Post $post, Report $report, ?string $additionalInstructions)
    {
        $response = OpenAI::responses()->create([
            'model' => 'gpt-5.1',
            'input' => [
                [
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.revise-post.developer')->render(),
                    ]],
                ],
                [
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.revise-post.user', [
                            'post' => $post,
                            'report' => $report,
                            'additionalInstructions' => $additionalInstructions,
                        ])->render(),
                    ]],
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'blog_post',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => [
                                'type' => 'string',
                                'description' => 'The title of the blog post.',
                            ],
                            'serp_title' => [
                                'type' => 'string',
                                'description' => "A title optimized for CTR on Google's SERP.",
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => "A description of the blog post optimized for CTR on Google's SERP.",
                            ],
                            'content' => [
                                'type' => 'string',
                                'description' => 'The content of the blog post. Do not include the title, Front Matter, or any JSON-LD/structured data.',
                            ],
                        ],
                        'required' => [
                            'title',
                            'serp_title',
                            'description',
                            'content',
                        ],
                        'additionalProperties' => false,
                    ],
                ],
                'verbosity' => 'high',
            ],
            'reasoning' => [
                'effort' => 'high',
                'summary' => 'auto',
            ],
            'tools' => [[
                'type' => 'web_search_preview',
                'search_context_size' => 'high',
                'user_location' => [
                    'type' => 'approximate',
                    'country' => 'US',
                ],
            ]],
            'store' => true,
        ]);

        $data = json_decode($response->outputText, true, JSON_THROW_ON_ERROR);

        $revision = Revision::query()->create([
            'report_id' => $report->id,
            'data' => $data,
        ]);

        $report->update(['completed_at' => now()]);

        User::query()
            ->where('github_login', 'benjamincrozat')
            ->first()
            ?->notify(new NewRevision($revision));

        return $revision;
    }
}
