<x-guest-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('guest.home') }}" class="mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-lg font-bold">Video Peninggalan</h1>
            </div>
            <x-user-profile-navbar/>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="px-6 py-6 bg-gray-50 min-h-screen">
        @if($videos->count() > 0)
            <div class="space-y-4">
                @foreach($videos as $video)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <a href="{{ route('guest.video-peninggalan.show', $video->id) }}" class="block">
                            <div class="flex">
                                {{-- Thumbnail --}}
                                <div class="w-32 h-24 flex-shrink-0 relative">
                                    @if($video->thumbnail)
                                        <img src="{{ asset('storage/' . $video->thumbnail) }}"
                                             alt="{{ $video->judul }}"
                                             class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                                            <i class="fas fa-play text-white text-xl"></i>
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-video text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $video->judul }}</h3>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $video->deskripsi }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-video text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada video peninggalan</h3>
                <p class="text-sm text-gray-500">
                    Video peninggalan akan muncul di sini ketika tersedia.
                </p>
            </div>
        @endif
    </div>

    {{-- Bottom Navigation --}}
    <x-bottom-nav/>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-guest-layout>
