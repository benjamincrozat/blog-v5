{{--
Renders the livewire comment form view.
--}}

<div>
    <x-form wire:submit="submit" class="flex gap-4">
        <img
            loading="lazy"
            src="{{ auth()->check() ? $user->avatar : 'https://www.gravatar.com/avatar/?d=mp' }}"
            alt="{{ auth()->check() ? $user->name : 'Guest' }}"
            class="flex-none mt-1 rounded-full ring-1 shadow-sm shadow-black/5 ring-black/10 size-7 md:size-8"
        />

        <div class="grow">
            <label for="comment" class="text-sm font-bold uppercase">
                {{ $label }}
            </label>

            <textarea
                id="comment"
                wire:model="commentContent"
                @if (auth()->guest())
                    disabled
                @endif
                required
                wire:loading.attr="disabled"
                class="px-3 py-2 mt-1 w-full placeholder-gray-300 rounded-md border border-gray-200 shadow-sm resize-none shadow-black/5 disabled:text-gray-300"
                @if ($parentId)
                    x-init="$el.focus()"
                @endif
                x-autosize
            ></textarea>

            <p class="text-xs text-gray-500">
                Markdown is supported.
            </p>

            @if (auth()->check())
                <div class="flex gap-2 justify-center items-center mt-4">
                    <button
                        class="font-medium tracking-tight text-white bg-blue-600 rounded-xl transition-colors disabled:bg-gray-100 disabled:hover:bg-gray-100! disabled:text-gray-300! px-[1.3rem] py-[.65rem] hover:bg-blue-500"
                        wire:loading.attr="disabled"
                    >
                        {{ $parentId ? 'Reply' : 'Comment' }}
                    </button>

                    @if ($parentId)
                        <x-btn
                            type="button"
                            class="-order-1"
                            @click="$wire.$parent.$set('parentId', null)"
                        >
                            Cancel
                        </x-btn>
                    @endif
                </div>
            @else
                <p class="mt-8">
                    Hey, you need to sign in with your GitHub account to comment.

                    <a wire:navigate href="{{ route('auth.redirect') }}" class="font-medium underline">
                        Get&nbsp;started&nbsp;→
                    </a>
                </p>
            @endif
        </div>
    </x-form>
</div>
