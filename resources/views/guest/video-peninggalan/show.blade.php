<x-guest-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('guest.video-peninggalan') }}" class="mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-lg font-bold">{{ __('app.heritage_video') }}</h1>
            </div>
            <x-user-profile-navbar/>
        </div>
    </div>

    {{-- Video Player Section --}}
    <div class="bg-black">
        @if($video->link && file_exists(storage_path('app/public/' . $video->link)))
            <video controls class="w-full aspect-video" preload="metadata" controlsList="nodownload">
                <source src="{{ asset('storage/' . $video->link) }}" type="video/mp4">
                <source src="{{ asset('storage/' . $video->link) }}" type="video/webm">
                <source src="{{ asset('storage/' . $video->link) }}" type="video/mov">
                {{ __('app.video_browser_not_supported') }}
            </video>
        @else
            <div class="w-full aspect-video bg-gray-800 flex items-center justify-center">
                <div class="text-center text-white">
                    <i class="fas fa-video text-4xl mb-4 opacity-50"></i>
                    <p>{{ __('app.video_not_available') }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Video Info Section --}}
    <div class="px-6 py-6 bg-white">
        <h2 class="text-xl font-bold text-gray-900 mb-3">{{ $video->judul }}</h2>
        <div class="prose prose-sm text-gray-700">
            <p class="whitespace-pre-line">{{ $video->deskripsi }}</p>
        </div>
    </div>

    {{-- Additional Content Area (if needed for future features) --}}
    <div class="px-6 py-4 bg-gray-50">
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-2">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                {{ __('app.video_about_this') }}
            </h3>
            <p class="text-sm text-gray-600">
                {{ __('app.video_heritage_desc') }}
            </p>
        </div>
    </div>

    {{-- Bottom padding for navigation --}}
    <div class="h-20"></div>

    {{-- Bottom Navigation --}}
    <x-bottom-nav class="md:hidden"/>

    <style>
        .aspect-video {
            aspect-ratio: 16 / 9;
        }

        .prose p {
            margin-bottom: 1rem;
        }

        video::-webkit-media-controls-download-button {
            display: none;
        }

        video::-webkit-media-controls-enclosure {
            overflow: hidden;
        }

        video::-webkit-media-controls-panel {
            width: calc(100% + 30px);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.querySelector('video');
            if (video) {
                // Disable right-click context menu on video
                video.addEventListener('contextmenu', e => e.preventDefault());

                // Optional: Add loading indicator
                video.addEventListener('loadstart', function() {
                    console.log('Video loading started');
                });

                video.addEventListener('canplay', function() {
                    console.log('Video can start playing');
                });

                // Error handling
                video.addEventListener('error', function(e) {
                    console.error('Video error:', e);
                    // You could show a user-friendly error message here
                });
            }
        });
    </script>
</x-guest-layout>
