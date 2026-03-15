<x-elearning-layout>
    {{-- Header Section --}}
    <div class="bg-primary px-6 py-6 text-white">
        <div class="mb-6 flex items-center justify-between">
            <button class="back-button rounded-full p-2 transition-colors hover:bg-white/10"
                data-fallback-url="{{ route('guest.home') }}">
                <i class="fas fa-arrow-left text-xl"></i>
            </button>
            <a href="{{ route('profile.edit') }}"
                class="h-12 w-12 overflow-hidden rounded-full border-2 border-white/30 transition-colors hover:border-white/50">
                @if (auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Picture"
                        class="h-full w-full object-cover" />
                @else
                    <img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="Profile Picture"
                        class="h-full w-full object-cover" />
                @endif
            </a>
        </div>

        <h1 class="mb-8 text-2xl font-bold">{{ __('app.elearning') }}</h1>

        {{-- Continuing Card --}}
        @if ($nextMateri)
            <div class="relative mb-6 overflow-hidden rounded-2xl bg-blue-500 p-6">
                <div class="flex items-center space-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-400">
                        <i class="fas fa-lightbulb text-xl text-gray-800"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-blue-100">Lanjutkan dari tempat Anda tinggalkan</p>
                        @php
                            $stepLabel = 'Pre-test';
                            if (auth()->user()->progress_level_sekarang == 1) {
                                $stepLabel = 'E-Book';
                            } elseif (auth()->user()->progress_level_sekarang == 2) {
                                $stepLabel = 'Virtual Living Museum';
                            } elseif (auth()->user()->progress_level_sekarang == 3) {
                                $stepLabel = 'Post-test';
                            }
                        @endphp
                        <h3 class="font-semibold text-white">{{ $stepLabel }}</h3>
                        <h3 class="font-bold text-white">{{ $nextMateri->judul }}</h3>
                    </div>
                    @php
                        $tabParam = 'pretest';
                        if (auth()->user()->progress_level_sekarang == 1) {
                            $tabParam = 'ebook';
                        } elseif (auth()->user()->progress_level_sekarang == 2) {
                            $tabParam = 'museum';
                        } elseif (auth()->user()->progress_level_sekarang == 3) {
                            $tabParam = 'posttest';
                        }
                    @endphp
                    <a href="{{ route('guest.elearning.materi', $nextMateri->materi_id) }}?tab={{ $tabParam }}"
                        class="rounded-full p-2 transition-colors hover:bg-white/10">
                        <i class="fas fa-arrow-right text-xl text-white"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Content Section --}}
    <div class="relative -mt-6 min-h-screen rounded-t-3xl bg-white">
        {{-- Tab Navigation --}}
        <div class="px-6 pt-6">
            <div class="flex rounded-2xl bg-gray-100 p-1">
                <button id="tab-materi"
                    class="tab-button active flex-1 rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    Materi
                </button>
                <button id="tab-riwayat"
                    class="tab-button flex-1 rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200">
                    Riwayat
                </button>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="px-6 py-6">
            {{-- Materi Tab Content --}}
            <div id="content-materi" class="tab-content">
                @php
                    $groupedMateri = $materis->groupBy(function ($m) {
                        return $m->era_id ?? 0;
                    });
                @endphp
                @if ($groupedMateri->isEmpty())
                    <div class="rounded-2xl bg-white p-12 text-center shadow-sm">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                            <i class="fas fa-book text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="mb-2 text-lg font-medium text-gray-900">Belum Ada Materi</h3>
                        <p class="text-gray-600">Materi pembelajaran akan segera tersedia.</p>
                    </div>
                @else
                    @foreach ($groupedMateri as $eraId => $eraMateri)
                        @php $era = $eraMateri->first()->era; @endphp
                        {{-- Era Group Header --}}
                        <div class="mb-3 mt-6 flex items-center gap-3 first:mt-0">
                            <div class="h-px flex-1 bg-blue-100"></div>
                            <div
                                class="flex min-w-0 flex-col items-center rounded-xl border border-blue-200 bg-blue-50 px-3 py-1.5 text-center">
                                @if ($era)
                                    <span
                                        class="text-xs font-bold uppercase tracking-wide text-blue-700">{{ $era->kode }}.
                                        {{ $era->nama }}</span>
                                    @if ($era->rentang_waktu)
                                        <span class="text-[11px] text-blue-500">{{ $era->rentang_waktu }}</span>
                                    @endif
                                @else
                                    <span class="text-xs font-bold uppercase tracking-wide text-gray-600">Materi</span>
                                @endif
                            </div>
                            <div class="h-px flex-1 bg-blue-100"></div>
                        </div>

                        <div class="space-y-3">
                            @foreach ($eraMateri as $materi)
                                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                                    <div class="flex">
                                        {{-- Gambar Sampul --}}
                                        <div class="h-20 w-20 flex-shrink-0">
                                            @if ($materi->gambar_sampul)
                                                <img src="{{ asset('storage/' . $materi->gambar_sampul) }}"
                                                    alt="{{ $materi->judul }}" class="h-full w-full object-cover">
                                            @else
                                                <div
                                                    class="flex h-full w-full items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600">
                                                    <i class="fas fa-book text-xl text-white"></i>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-1 p-4">
                                            @if ($materi->bab)
                                                <span
                                                    class="mb-1 inline-block rounded bg-blue-50 px-1.5 py-0.5 text-[11px] font-medium text-blue-600">Bab
                                                    {{ $materi->bab }}</span>
                                            @endif
                                            <h3 class="mb-1 font-semibold text-gray-900">{{ $materi->judul }}</h3>
                                            <p class="mb-3 line-clamp-2 text-sm text-gray-600">{{ $materi->deskripsi }}
                                            </p>

                                            {{-- Action Button --}}
                                            @if ($materi->is_available)
                                                <a href="{{ route('guest.elearning.materi', $materi->materi_id) }}"
                                                    class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-blue-700">
                                                    @if ($materi->is_completed)
                                                        <i class="fas fa-redo mr-1"></i>
                                                        Lihat
                                                    @else
                                                        <i class="fas fa-play mr-1"></i>
                                                        {{ $materi->is_started ?? false ? 'Lanjutkan' : 'Mulai' }}
                                                    @endif
                                                </a>
                                            @else
                                                <div
                                                    class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-400">
                                                    <i class="fas fa-lock mr-1"></i>
                                                    Terkunci
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Riwayat Tab Content --}}
            <div id="content-riwayat" class="tab-content hidden">
                <div class="space-y-10">
                    @forelse($riwayatAktivitas as $tanggal => $aktivitas)
                        {{-- Date Header --}}
                        <div class="relative my-4">
                            <div class="absolute left-0 right-0 top-1/2 h-px -translate-y-1/2 bg-gray-200"></div>
                            <div class="relative z-10 mx-auto w-28">
                                <div class="text-center text-[11px] font-medium uppercase tracking-wide text-gray-500">
                                    {{ strtoupper(date('M', strtotime($tanggal))) }}
                                    {{ date('Y', strtotime($tanggal)) }}
                                </div>
                                <div
                                    class="rounded-full border border-gray-200 bg-white px-6 py-3 text-center shadow-md">
                                    <div class="text-xl font-bold leading-none text-gray-900">
                                        {{ date('d', strtotime($tanggal)) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Activities --}}
                        <div class="space-y-6">
                            @foreach ($aktivitas as $item)
                                <div class="relative flex items-start">
                                    {{-- Time --}}
                                    <div class="w-16 flex-shrink-0 pr-4 pt-1 text-right">
                                        <div class="text-sm font-semibold text-gray-500">
                                            {{ date('H:i', strtotime($item->created_at)) }}
                                        </div>
                                    </div>

                                    {{-- Timeline Column --}}
                                    <div class="flex w-4 justify-center">
                                        {{-- Dot --}}
                                        <div
                                            class="z-10 mt-1 h-3 w-3 rounded-full border-2 border-white bg-blue-500 shadow">
                                        </div>
                                    </div>

                                    {{-- Activity Content --}}
                                    <div
                                        class="ml-3 flex-1 rounded-xl border border-gray-100 bg-white p-4 shadow transition-all hover:-translate-y-0.5 hover:shadow-md">
                                        <div class="flex items-start">
                                            {{-- Icon --}}
                                            <div
                                                class="mr-3 flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600 shadow-sm">
                                                @if (str_contains(strtolower($item->aktivitas), 'materi'))
                                                    <i class="fas fa-book text-sm"></i>
                                                @elseif(str_contains(strtolower($item->aktivitas), 'selesai') ||
                                                        str_contains(strtolower($item->aktivitas), 'telah') ||
                                                        str_contains(strtolower($item->aktivitas), 'menuntaskan'))
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
                                    @if (!$loop->last)
                                        <div class="absolute bottom-0 left-[4.5rem] top-5 w-px bg-gray-300"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="rounded-2xl border border-gray-100 bg-white p-10 text-center shadow">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-500 shadow-sm">
                                <i class="fas fa-history text-2xl"></i>
                            </div>
                            <h3 class="mb-1 text-lg font-semibold text-gray-900">Belum Ada Riwayat</h3>
                            <p class="text-sm text-gray-500">Aktivitas pembelajaran Anda akan muncul di sini.</p>
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
                        btn.classList.remove('active', 'bg-white', 'text-gray-900',
                            'shadow-sm');
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
