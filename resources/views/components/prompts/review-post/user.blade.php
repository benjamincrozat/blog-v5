{{--
Renders the components prompts review post user view.
--}}

{{ $post->toMarkdown() }}

@if ($additionalInstructions)
Additional instructions: {{ $additionalInstructions }}
@endif
