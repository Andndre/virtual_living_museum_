<x-guest-layout>
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex items-center">
            <a href="{{ route('guest.home') }}" class="mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
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
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Kirim Kritik & Saran</h2>
            
            <form action="{{ route('guest.kritik-saran.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="pesan" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                    <textarea id="pesan" name="pesan" rows="4" 
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-primary focus:border-primary
                        {{ $errors->has('pesan') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="Tuliskan kritik dan saran Anda di sini...">{{ old('pesan') }}</textarea>
                    
                    @error('pesan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Kirim
                    </button>
                </div>
            </form>
        </div>

        {{-- History Section --}}
        <div class="bg-white rounded-lg shadow-sm p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Kritik & Saran</h2>
            
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
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <p class="text-gray-500">Anda belum memiliki riwayat kritik dan saran.</p>
                </div>
            @endif
        </div>
    </div>
    
    {{-- Bottom Navigation --}}
    <x-bottom-nav />
</x-guest-layout>
