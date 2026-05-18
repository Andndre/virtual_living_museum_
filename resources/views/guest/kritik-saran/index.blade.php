<x-guest-layout>
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex items-center">
            <button class="back-button mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </button>
            <h1 class="text-xl font-bold">{{ __('app.feedback_suggestion') }}</h1>
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen pt-6 px-6 pb-24">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Section --}}
        <div class="bg-white rounded-lg shadow-sm p-5 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('app.send_feedback') }}</h2>

            <form action="{{ route('guest.kritik-saran.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="pesan" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.message') }}</label>
                    <textarea id="pesan" name="pesan" rows="4"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-primary focus:border-primary
                        {{ $errors->has('pesan') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="{{ __('app.write_feedback_placeholder') }}">{{ old('pesan') }}</textarea>
                    @error('pesan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-md transition-colors">
                        {{ __('app.send') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- History Section --}}
        <div class="bg-white rounded-lg shadow-sm p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('app.feedback_history') }}</h2>

            @if ($kritikSaran->count() > 0)
                <div class="space-y-4">
                    @foreach ($kritikSaran as $item)
                        <div class="border border-gray-200 p-4 rounded-lg">
                            <div class="flex justify-between items-start">
                                <p class="text-gray-800">{{ $item->pesan }}</p>
                                <span class="text-xs text-gray-500">{{ $item->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl bg-white p-12 text-center shadow-sm">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                        <i class="fas fa-comment-dots text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="mb-2 text-lg font-medium text-gray-900">Belum Ada Feedback</h3>
                    <p class="text-gray-600">{{ __('app.no_feedback_history') }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Bottom Navigation --}}
    <x-bottom-nav class="md:hidden" />
</x-guest-layout>
