<?php

namespace App\Actions;

use App\Models\Post;
use OpenAI\Laravel\Facades\OpenAI;

/**
 * Defines the RecommendPosts implementation.
 */
class RecommendPosts
{
    /**
     * Ask GPT to recommend more reading material for a given post.
     */
    public function recommend(Post $post) : void
    {
        $candidates = Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->get();

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4.1-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => view('components.prompts.get-recommended-posts.developer')->render(),
                ],
                [
                    'role' => 'user',
                    'content' => view('components.prompts.get-recommended-posts.user', compact('post', 'candidates'))->render(),
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'recommendations',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'recommendations' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'reason' => [
                                            'type' => 'string',
                                            'description' => 'The reason why the post is recommended.',
                                        ],
                                        'id' => [
                                            'type' => 'number',
                                            'description' => 'Unique identifier for the post.',
                                        ],
                                        'title' => [
                                            'type' => 'string',
                                            'description' => 'The title of the post.',
                                        ],
                                    ],
                                    'required' => [
                                        'id',
                                        'title',
                                        'reason',
                                    ],
                                    'additionalProperties' => false,
                                ],
                            ],
                        ],
                        'required' => [
                            'recommendations',
                        ],
                        'additionalProperties' => false,
                    ],
                    'strict' => true,
                ],
            ],
        ]);

        $post->update([
            'recommendations' => json_decode($response->choices[0]->message->content)->recommendations,
        ]);
    }
}
