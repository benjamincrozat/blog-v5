<?php

namespace App\Actions;

use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Notifications\NewReport;
use OpenAI\Laravel\Facades\OpenAI;

class ReviewPost
{
    /**
     * Review a post, generate a report, and notify the admin.
     */
    public function review(Post $post, ?string $additionalInstructions) : Report
    {
        $response = OpenAI::responses()->create([
            'model' => 'gpt-5.1',
            'input' => [
                [
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.review-post.developer')->render(),
                    ]],
                ],
                [
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.review-post.user', compact('post', 'additionalInstructions'))->render(),
                    ]],
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'text',
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

        $report = Report::query()->create([
            'post_id' => $post->id,
            'content' => $response->outputText,
        ]);

        User::query()
            ->where('github_login', 'benjamincrozat')
            ->first()
            ?->notify(new NewReport($report));

        return $report;
    }
}
