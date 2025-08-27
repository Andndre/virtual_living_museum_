<x-elearning-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center mb-6">
            <button class="back-button p-2 hover:bg-white/10 rounded-full transition-colors" data-fallback-url="{{ route('guest.home') }}">
                <i class="fas fa-arrow-left text-xl"></i>
            </button>
            <a href="{{ route('profile.edit') }}" class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/30 hover:border-white/50 transition-colors">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Picture" class="w-full h-full object-cover" />
                @else
                    <img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="Profile Picture" class="w-full h-full object-cover" />
                @endif
            </a>
        </div>

        <h1 class="text-2xl font-bold mb-8">{{ __('app.elearning') }}</h1>

        {{-- Continuing Card --}}
        @if($nextMateri)
        <div class="bg-blue-500 rounded-2xl p-6 mb-6 relative overflow-hidden">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-yellow-400 rounded-xl flex items-center justify-center">
                    <i class="fas fa-lightbulb text-gray-800 text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-blue-100 text-sm">Lanjutkan dari tempat Anda tinggalkan</p>
                    @php
                        $stepLabel = 'Pre-test';
                        if(auth()->user()->progress_level_sekarang == 1) $stepLabel = 'E-Book';
                        elseif(auth()->user()->progress_level_sekarang == 2) $stepLabel = 'Virtual Living Museum';
                        elseif(auth()->user()->progress_level_sekarang == 3) $stepLabel = 'Post-test';
                    @endphp
                    <h3 class="text-white font-semibold">{{ $stepLabel }}</h3>
                    <h3 class="text-white font-bold">{{ $nextMateri->judul }}</h3>
                </div>
                @php
                    $tabParam = 'pretest';
                    if(auth()->user()->progress_level_sekarang == 1) $tabParam = 'ebook';
                    elseif(auth()->user()->progress_level_sekarang == 2) $tabParam = 'museum';
                    elseif(auth()->user()->progress_level_sekarang == 3) $tabParam = 'posttest';
                @endphp
                <a href="{{ route('guest.elearning.materi', $nextMateri->materi_id) }}?tab={{ $tabParam }}" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                    <i class="fas fa-arrow-right text-white text-xl"></i>
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- Content Section --}}
    <div class="bg-white rounded-t-3xl -mt-6 relative min-h-screen">
        {{-- Tab Navigation --}}
        <div class="px-6 pt-6">
            <div class="flex bg-gray-100 rounded-2xl p-1">
                <button id="tab-materi" class="flex-1 py-3 px-4 text-sm font-medium rounded-xl transition-all duration-200 tab-button active">
                    Materi
                </button>
                <button id="tab-riwayat" class="flex-1 py-3 px-4 text-sm font-medium rounded-xl transition-all duration-200 tab-button">
                    Riwayat
                </button>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="px-6 py-6">
            {{-- Materi Tab Content --}}
            <div id="content-materi" class="tab-content">
                <div class="space-y-4">
                    @forelse($materis as $index => $materi)
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
                            <div class="flex">
                                {{-- Gambar Sampul --}}
                                <div class="w-20 h-20 flex-shrink-0">
                                    @if($materi->gambar_sampul)
                                        <img src="{{ asset('storage/' . $materi->gambar_sampul) }}" alt="{{ $materi->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                            <i class="fas fa-book text-white text-xl"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 p-4">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $materi->judul }}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $materi->deskripsi }}</p>

                                    {{-- Action Button --}}
                                    @if($materi->is_available)
                                        <a href="{{ route('guest.elearning.materi', $materi->materi_id) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            @if($materi->is_completed)
                                                <i class="fas fa-redo mr-1"></i>
                                                Lihat
                                            @elseif($materi->is_started)
                                                <i class="fas fa-play mr-1"></i>
                                                Lanjutkan
                                            @else
                                                <i class="fas fa-play mr-1"></i>
                                                Mulai
                                            @endif
                                        </a>
                                    @else
                                        <div class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-400 text-xs font-medium rounded-lg">
                                            <i class="fas fa-lock mr-1"></i>
                                            Terkunci
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-book text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Materi</h3>
                            <p class="text-gray-600">Materi pembelajaran akan segera tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Riwayat Tab Content --}}
            <div id="content-riwayat" class="tab-content hidden">
                <div class="space-y-10">
                    @forelse($riwayatAktivitas as $tanggal => $aktivitas)
                        {{-- Date Header --}}
                        <div class="relative my-4">
                            <div class="absolute left-0 right-0 h-px bg-gray-200 top-1/2 -translate-y-1/2"></div>
                            <div class="relative z-10 w-28 mx-auto">
                                <div class="text-[11px] font-medium text-center tracking-wide text-gray-500 uppercase">
                                    {{ strtoupper(date('M', strtotime($tanggal))) }} {{ date('Y', strtotime($tanggal)) }}
                                </div>
                                <div class="bg-white px-6 py-3 rounded-full border border-gray-200 shadow-md text-center">
                                    <div class="text-xl font-bold text-gray-900 leading-none">
                                        {{ date('d', strtotime($tanggal)) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Activities --}}
                        <div class="space-y-6">
                            @foreach($aktivitas as $item)
                                <div class="flex items-start relative">
                                    {{-- Time --}}
                                    <div class="w-16 flex-shrink-0 text-right pr-4 pt-1">
                                        <div class="text-sm font-semibold text-gray-500">
                                            {{ date('H:i', strtotime($item->created_at)) }}
                                        </div>
                                    </div>

                                    {{-- Timeline Column --}}
                                    <div class="w-4 flex justify-center">
                                        {{-- Dot --}}
                                        <div class="w-3 h-3 bg-blue-500 border-2 border-white shadow rounded-full mt-1 z-10"></div>
                                    </div>

                                    {{-- Activity Content --}}
                                    <div class="ml-3 flex-1 bg-white border border-gray-100 rounded-xl p-4 shadow hover:shadow-md hover:-translate-y-0.5 transition-all">
                                        <div class="flex items-start">
                                            {{-- Icon --}}
                                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3 shadow-sm">
                                                @if(str_contains(strtolower($item->aktivitas), 'materi'))
                                                    <i class="fas fa-book text-sm"></i>
                                                @elseif(str_contains(strtolower($item->aktivitas), 'selesai') || str_contains(strtolower($item->aktivitas), 'telah') || str_contains(strtolower($item->aktivitas), 'menuntaskan'))
                                                    <i class="fas fa-check-circle text-sm text-green-600"></i>
                                                @elseif(str_contains(strtolower($item->aktivitas), 'mulai'))
                                                    <i class="fas fa-play-circle text-sm text-indigo-600"></i>
                                                @else
                                                    <i class="fas fa-history text-sm text-gray-500"></i>
                                                @endif
                                            </div>

                                            {{-- Text --}}
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $item->aktivitas }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Vertical Line --}}
                                    @if(!$loop->last)
                                        <div class="absolute left-[4.5rem] top-5 bottom-0 w-px bg-gray-300"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow p-10 text-center border border-gray-100">
                            <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 shadow-sm">
                                <i class="fas fa-history text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Belum Ada Riwayat</h3>
                            <p class="text-gray-500 text-sm">Aktivitas pembelajaran Anda akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for Tabs --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.id.replace('tab-', '');

                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-white', 'text-gray-900', 'shadow-sm');
                        btn.classList.add('text-gray-500');
                    });

                    // Add active class to clicked button
                    this.classList.add('active', 'bg-white', 'text-gray-900', 'shadow-sm');
                    this.classList.remove('text-gray-500');

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show selected tab content
                    const targetContent = document.getElementById(`content-${tabId}`);
                    if (targetContent) {
                        targetContent.classList.remove('hidden');
                    }
                });
            });

            // Set initial active state
            const defaultTab = document.getElementById('tab-materi');
            if (defaultTab) {
                defaultTab.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
                defaultTab.classList.remove('text-gray-500');
            }
        });
    </script>

    <style>
        .tab-button {
            transition: all 0.2s ease-in-out;
        }

        .tab-button:not(.active) {
            color: #6B7280;
        }

        .tab-button.active {
            background-color: white;
            color: #111827;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
</x-elearning-layout>
