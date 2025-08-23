<x-guest-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center">
            <a href="{{ route('guest.home') }}"
                class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fas fa-arrow-left text-white"></i>
            </a>
            <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/30">
                @if (auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Picture"
                        class="w-full h-full object-cover" />
                @else
                    <img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="Profile Picture"
                        class="w-full h-full object-cover" />
                @endif
            </div>
        </div>

        <div class="pt-8 pb-4 flex items-center">
            <img src="{{ asset('images/mascot.png') }}" alt="Mascot"
                class="w-32 h-32 md:w-40 md:h-40 object-contain mr-4 drop-shadow-xl" />
            <div>
                <h2 class="text-lg md:text-xl font-bold mb-2">Hai sobat sejarah....</h2>
                <p class="text-white/90 text-sm md:text-base leading-relaxed">
                    Virtual tour dapat digunakan dengan 2 fitur lhoo, akses langsung menggunakan maps pada lokasi
                    tertentu atau dengan fitur pembabakan sesuai dengan kategori peninggalan sejarah.
                </p>
            </div>
        </div>
    </div>

    {{-- Card Menu Section --}}
    <div class="px-6 py-8 bg-white rounded-t-3xl -mt-6 relative min-h-[60vh] flex flex-col items-center">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Pilih Menu</h3>
        <div class="flex w-full max-w-lg gap-8 justify-center">
            {{-- Maps --}}
            <a href="{{ route('guest.maps.view') }}"
                class="flex flex-col items-center justify-center bg-gray-50 rounded-2xl shadow-md hover:shadow-lg transition w-full py-8 mx-2">
                <img src="{{ asset('images/icons/download-katalog.png') }}" alt="Maps" class="w-16 h-16 mb-4" />
                <span class="text-base font-medium text-gray-700 text-center block mt-2">Maps</span>
            </a>
            {{-- Peninggalan Kebudayaan Bali --}}
            <a href="{{ route('guest.maps.peninggalan') }}"
                class="flex flex-col items-center justify-center bg-gray-50 rounded-2xl shadow-md hover:shadow-lg transition w-full py-8 mx-2">
                <img src="{{ asset('images/icons/ar-marker.png') }}" alt="Peninggalan Kebudayaan Bali"
                    class="w-16 h-16 mb-4" />
                <span class="text-base font-medium text-gray-700 text-center block mt-2">Peninggalan Kebudayaan
                    Bali</span>
            </a>
        </div>
    </div>
</x-guest-layout>
