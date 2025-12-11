<?php

namespace App\Actions\Jobs;

use App\Scraper\Webpage;
use OpenAI\Laravel\Facades\OpenAI;

class FetchJobData
{
    public function fetch(Webpage $webpage) : array
    {
        $response = OpenAI::responses()->create([
            'model' => 'gpt-5.1',
            'input' => [
                [
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.fetch-job-data.developer')->render(),
                    ]],
                ],
                [
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.fetch-job-data.user', compact('webpage'))->render(),
                    ]],
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'job',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'url' => [
                                'type' => 'string',
                                'description' => 'Direct link to the job posting.',
                                'minLength' => 1,
                            ],
                            'language' => [
                                'type' => 'string',
                                'description' => "Language code of the original job posting in ISO 639 format, for example 'en', 'fr', or 'de'.",
                                'pattern' => '^[a-zA-Z]{2,3}(-[a-zA-Z]{2,3})?$',
                                'minLength' => 2,
                            ],
                            'title' => [
                                'type' => 'string',
                                'description' => 'The title of the job. In the original language, without the company name, locations, or work setting. Make it specific and distinctive, using at most 12 words. Always include at least one of the main technologies (for example, "Rust", "Laravel", "React"), include the skill level when seniority is known (for example, "Junior", "Mid-level", "Senior", "Lead"), and avoid generic titles like "Senior backend developer" or "Software Engineer".',
                                'minLength' => 1,
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => 'A concise but complete summary of the job. In the original language, without the company name, locations, or work setting. Address the candidate as "you" and refer to the employer as "they" or "the company" (not "we"). Focus on the information a candidate needs to decide whether to apply (for example, mission, main responsibilities, key skills, important constraints or expectations), without omitting anything essential.',
                                'minLength' => 1,
                            ],
                            'technologies' => [
                                'type' => 'array',
                                'description' => 'The technologies required for the job.',
                                'items' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                ],
                            ],
                            'locations' => [
                                'type' => 'array',
                                'description' => 'Array of display-ready location strings built from the structured entries. Can be empty.',
                                'items' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                ],
                                'minItems' => 0,
                            ],
                            'location_entities' => [
                                'type' => 'array',
                                'description' => 'Structured locations to store or reuse Location records.',
                                'items' => [
                                    '$ref' => '#/$defs/location',
                                ],
                                'minItems' => 0,
                            ],
                            'setting' => [
                                'type' => 'string',
                                'description' => 'Work setting: fully-remote, hybrid, or on-site.',
                                'enum' => [
                                    'fully-remote',
                                    'hybrid',
                                    'on-site',
                                ],
                            ],
                            'employment_status' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'description' => 'Employment status for this role. Use one of: full-time, part-time, contract, temporary, internship, freelance, other.',
                                        'enum' => [
                                            'full-time',
                                            'part-time',
                                            'contract',
                                            'temporary',
                                            'internship',
                                            'freelance',
                                            'other',
                                        ],
                                    ],
                                    [
                                        'type' => 'null',
                                        'description' => 'Null if employment status is not mentioned or cannot be confidently inferred from the source.',
                                    ],
                                ],
                            ],
                            'seniority' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'description' => 'Seniority level for this role. Use one of: intern, junior, mid-level, senior, lead, principal, executive.',
                                        'enum' => [
                                            'intern',
                                            'junior',
                                            'mid-level',
                                            'senior',
                                            'lead',
                                            'principal',
                                            'executive',
                                        ],
                                    ],
                                    [
                                        'type' => 'null',
                                        'description' => 'Null if seniority is not mentioned or cannot be confidently inferred from the source.',
                                    ],
                                ],
                            ],
                            'equity' => [
                                'type' => 'boolean',
                                'description' => 'Whether equity is offered for the role (true or false).',
                            ],
                            'min_salary' => [
                                'anyOf' => [
                                    [
                                        'type' => 'number',
                                        'description' => 'Minimum salary if provided.',
                                        'minimum' => 0,
                                    ],
                                    [
                                        'type' => 'null',
                                        'description' => 'Null if no minimum salary is provided.',
                                    ],
                                ],
                            ],
                            'max_salary' => [
                                'anyOf' => [
                                    [
                                        'type' => 'number',
                                        'description' => 'Maximum salary if provided.',
                                        'minimum' => 0,
                                    ],
                                    [
                                        'type' => 'null',
                                        'description' => 'Null if no maximum salary is provided.',
                                    ],
                                ],
                            ],
                            'currency' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'description' => 'The currency used for the salary.',
                                        'minLength' => 1,
                                    ],
                                    [
                                        'type' => 'null',
                                        'description' => 'Null if no currency is provided.',
                                    ],
                                ],
                            ],
                            'perks' => [
                                'type' => 'array',
                                'description' => 'Array of perks and benefits mentioned. Can be empty.',
                                'items' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                ],
                                'minItems' => 0,
                            ],
                            'interview_process' => [
                                'type' => 'array',
                                'description' => 'Array describing the interview process steps. Can be empty.',
                                'items' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                ],
                                'minItems' => 0,
                            ],
                            'company' => [
                                '$ref' => '#/$defs/company',
                            ],
                            'source' => [
                                'type' => 'string',
                                'description' => 'Name of the website or source where this job was found.',
                                'minLength' => 1,
                            ],
                        ],
                        'required' => [
                            'url',
                            'language',
                            'title',
                            'description',
                            'technologies',
                            'locations',
                            'location_entities',
                            'setting',
                            'employment_status',
                            'seniority',
                            'equity',
                            'min_salary',
                            'max_salary',
                            'currency',
                            'perks',
                            'interview_process',
                            'company',
                            'source',
                        ],
                        'additionalProperties' => false,
                        '$defs' => [
                            'company' => [
                                'type' => 'object',
                                'description' => 'Information about the company offering the job',
                                'properties' => [
                                    'name' => [
                                        'type' => 'string',
                                        'description' => 'Exact name of the company.',
                                        'minLength' => 1,
                                    ],
                                    'url' => [
                                        'anyOf' => [
                                            [
                                                'type' => 'string',
                                                'description' => 'Official company website or profile.',
                                                'minLength' => 1,
                                            ],
                                            [
                                                'type' => 'null',
                                                'description' => 'Null if no company URL is provided.',
                                            ],
                                        ],
                                    ],
                                    'logo' => [
                                        'anyOf' => [
                                            [
                                                'type' => 'string',
                                                'description' => 'An URL to the company logo image.',
                                                'minLength' => 1,
                                            ],
                                            [
                                                'type' => 'null',
                                                'description' => 'Null if no company logo is provided.',
                                            ],
                                        ],
                                    ],
                                    'about' => [
                                        'type' => 'string',
                                        'description' => 'What the company is about, based on web research. Include founding year, domain, notable products, and mission. Whatever you can find. Refer to the company as "they" or "the company" (not "we") and do not address the reader as "you". Remove any citation markers, footnote artifacts, or placeholders (e.g., "cite…", "[1]", "[citation needed]") so the text is clean prose.',
                                        'minLength' => 1,
                                    ],
                                ],
                                'required' => [
                                    'name',
                                    'url',
                                    'logo',
                                    'about',
                                ],
                                'additionalProperties' => false,
                            ],
                            'location' => [
                                'type' => 'object',
                                'description' => 'Normalized location components for storage or reuse.',
                                'properties' => [
                                    'city' => [
                                        'anyOf' => [
                                            [
                                                'type' => 'string',
                                                'description' => 'City name if present.',
                                                'minLength' => 1,
                                            ],
                                            [
                                                'type' => 'null',
                                                'description' => 'Null when city is missing.',
                                            ],
                                        ],
                                    ],
                                    'region' => [
                                        'anyOf' => [
                                            [
                                                'type' => 'string',
                                                'description' => 'Region, state, or province if present.',
                                                'minLength' => 1,
                                            ],
                                            [
                                                'type' => 'null',
                                                'description' => 'Null when region is missing.',
                                            ],
                                        ],
                                    ],
                                    'country' => [
                                        'type' => 'string',
                                        'description' => 'Full country name (for example, "United States", not "USA").',
                                        'minLength' => 1,
                                    ],
                                ],
                                'required' => [
                                    'city',
                                    'region',
                                    'country',
                                ],
                                'additionalProperties' => false,
                            ],
                        ],
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
            'include' => [
                'reasoning.encrypted_content',
                'web_search_call.action.sources',
            ],
        ]);

        return json_decode($response->outputText ?? '', associative: true) ?? [];
    }
}
