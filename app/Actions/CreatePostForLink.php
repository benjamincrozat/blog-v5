<?php

namespace App\Actions;

use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use RuntimeException;
use App\Jobs\RecommendPosts;
use Illuminate\Support\Facades\DB;
use OpenAI\Laravel\Facades\OpenAI;

class CreatePostForLink
{
    public function create(Link $link) : Post
    {
        $response = OpenAI::responses()->create([
            'model' => 'gpt-5',
            'input' => [
                [
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.create-post-for-link.developer')->render(),
                    ]],
                ],
                [
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.create-post-for-link.user', [
                            'url' => $link->url,
                            'notes' => $link->notes,
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
                                'description' => 'The title of the post.',
                            ],
                            'content' => [
                                'type' => 'string',
                                'description' => 'The content of the post.',
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => 'The meta description of the post. 160 characters or less.',
                            ],
                        ],
                        'required' => [
                            'title',
                            'content',
                            'description',
                        ],
                        'additionalProperties' => false,
                    ],
                ],
                'verbosity' => 'low',
            ],
            'reasoning' => [
                'effort' => 'low',
                'summary' => 'auto',
            ],
            'tools' => [[
                'type' => 'web_search_preview',
                'search_context_size' => 'low',
                'user_location' => [
                    'type' => 'approximate',
                    'country' => 'US',
                ],
            ]],
            'store' => true,
        ]);

        $json = json_decode($response->outputText ?? '', false);

        if (! is_object($json) || ! isset($json->title, $json->content, $json->description)) {
            throw new RuntimeException('Invalid model output.');
        }

        $ownerId = User::query()
            ->where('github_login', 'benjamincrozat')
            ->value('id');

        if (! $ownerId) {
            throw new RuntimeException('Benjamin Crozat user not found.');
        }

        return DB::transaction(function () use ($link, $json, $ownerId) {
            $post = Post::query()->create([
                'user_id' => $ownerId,
                'title' => (string) $json->title,
                'content' => (string) $json->content,
                'description' => (string) $json->description,
                'published_at' => $link->is_approved,
            ]);

            // Let's avoid orphans. If a new post is generated,
            // the old one does not need to exist anymore.
            if ($link->post) {
                $link->post->delete();
            }

            $link->update([
                'post_id' => $post->id,
            ]);

            RecommendPosts::dispatch($post)->afterCommit();

            return $post;
        });
    }
}
