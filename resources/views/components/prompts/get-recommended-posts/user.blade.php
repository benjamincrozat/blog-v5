{{--
Renders the components prompts get recommended posts user view.
--}}

Post: {{ $post->title }}
Post description: {{ $post->description }}
Post content: {{ $post->content }}

Other posts:
@foreach ($candidates as $post)
- {{ $post->id }}: {{ $post->title }}
  {{ $post->description }}
@endforeach
