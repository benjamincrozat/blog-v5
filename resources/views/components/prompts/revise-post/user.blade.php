{{--
Renders the components prompts revise post user view.
--}}

## Editor's Report

{{ $report->content }}

## Post

{{ $post->toMarkdown() }}

@if ($additionalInstructions)
## Additional Instructions

{{ $additionalInstructions }}
@endif
