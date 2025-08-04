<div>
    <div class="container sm:max-w-[480px]">
        {{-- Progress bar --}}
        <div>
            <p class="flex gap-[.35rem] items-center font-medium text-black">
                <span @class([
                    'text-blue-600' => ! $this->results,
                    'text-green-600' => $this->results,
                ])>{{ $this->currentStep }}</span>
                <span class="text-xs opacity-50">/</span>
                {{ $this->steps() }}
            </p>

            <div class="mt-2 mb-16 bg-gray-100 rounded-full">
                <div @class([
                    'h-2 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full shadow shadow-blue-300',
                    'from-green-400! to-green-600! shadow-green-300!' => true === $this->results,
                ]) style="width: {{ $this->currentStep / $this->steps() * 100 }}%"></div>
            </div>
        </div>

        {{-- Intro screen --}}
        @if (is_null($this->currentQuestion))
            <h1 class="font-medium tracking-tight text-center text-black text-balance text-xl/tight">
                {{ $quiz->title }}
            </h1>

            <x-prose class="mt-4 leading-normal">
                {!! Str::markdown($quiz->description) !!}
            </x-prose>

            <div class="mt-6 text-center">
                <x-btn
                    primary
                    wire:click="next"
                >
                    Start the quiz
                </x-btn>
            </div>
        @endif

        @if ($this->results)
            <div wire:key="results">
                <h1 class="font-medium tracking-tight text-center text-black text-balance text-xl/tight">
                    Results
                </h1>
            </div>
        {{-- Question screen --}}
        @elseif ($this->currentQuestion)
            <div wire:key="question-{{ $this->currentQuestion->id }}">
            <p class="text-xl font-medium leading-tight text-center text-balance">
                {{ $this->currentQuestion->question }}
            </p>

            <form wire:submit="next" class="grid gap-2 mt-4">
                @foreach ($this->currentQuestion->answers as $answer)
                    <label for="answer-{{ $answer->id }}" class="flex border border-gray-200 bg-gray-50 gap-3 items-center pl-3 pr-4 py-3 transition-colors leading-tight rounded-md has-[:checked]:border-blue-300 has-[:checked]:bg-blue-50/50 has-[:checked]:border">
                        <input
                            type="radio"
                            id="answer-{{ $answer->id }}"
                            name="answer"
                            value="{{ $answer->id }}"
                            wire:model.live="answers.{{ $this->currentQuestion->id }}"
                            required
                            class="border-0 ring-1 shadow transition-colors shadow-black/10 ring-black/5"
                        />

                        <span><strong class="font-medium text-black">{{ $loop->iteration }}.</strong> {{ $answer->answer }}</span>
                    </label>
                @endforeach

                @error('answers.' . $this->currentQuestion->id)
                    <p class="mt-2 font-normal text-red-600">{{ $message }}</p>
                @enderror

                @if ($this->hasNextStep)
                    <div class="mt-8 text-right">
                        <x-btn
                            primary
                            type="submit"
                            class="w-full md:w-auto"
                        >
                            Next
                        </x-btn>
                    </div>
                @endif
            </form>
            </div>
        @endif
    </div>
</div>
