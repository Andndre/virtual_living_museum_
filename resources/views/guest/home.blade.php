<x-guest-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center">
            <x-application-logo class="w-12 h-12"/>
            <x-user-profile-navbar/>
        </div>

        <div class="pt-8 pb-8">
            <p class="text-sm opacity-90">{{ $greeting }},</p>
            <h1 class="text-xl font-bold">{{ auth()->user()->name }}</h1>
        </div>
    </div>

    {{-- Menu Grid Section --}}
    <div class="px-6 py-8 pb-36 bg-gray-50 rounded-t-3xl -mt-6 relative">
        <div class="grid grid-cols-3 gap-6">
            {{-- E-Learning --}}
            <div class="flex flex-col items-center">
                <a href="{{ route('guest.elearning') }}"
                   class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
                    <img src="{{ asset('images/icons/elearning.png') }}" alt="E-Learning" class="w-12 h-12"/>
                </a>
                <span class="text-xs font-medium text-gray-700 text-center">{{ __('app.elearning') }}</span>
            </div>

            {{-- Maps --}}
            <div class="flex flex-col items-center">
                <a href="{{ route('guest.maps') }}"
                   class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
                    <img src="{{ asset('images/icons/maps.png') }}" alt="Maps" class="w-12 h-12"/>
                </a>
                <span class="text-xs font-medium text-gray-700 text-center">{{ __('app.maps') }}</span>
            </div>

            {{-- Statistik --}}
            <div class="flex flex-col items-center">
                <a href="{{ route('guest.statistik') }}"
                   class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
                    <img src="{{ asset('images/icons/statistik.png') }}" alt="Statistik" class="w-12 h-12"/>
                </a>
                <span class="text-xs font-medium text-gray-700 text-center">{{ __('app.statistics') }}</span>
            </div>

            {{-- Lapor Peninggalan --}}
            <div class="flex flex-col items-center">
                <a href="{{ route('guest.laporan-peninggalan') }}"
                   class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
                    <img src="{{ asset('images/icons/lapor-peninggalan.png') }}" alt="Lapor Peninggalan"
                         class="w-12 h-12"/>
                </a>
                <span class="text-xs font-medium text-gray-700 text-center">{{ __('app.report_heritage') }}</span>
            </div>

            {{-- Video Peninggalan --}}
            <div class="flex flex-col items-center">
                <a href="#"
                   class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
                    <img src="{{ asset('images/icons/video-peninggalan.png') }}" alt="Video Peninggalan"
                         class="w-12 h-12"/>
                </a>
                <span class="text-xs font-medium text-gray-700 text-center">{{ __('app.heritage_video') }}</span>
            </div>

            {{-- Kritik & Saran --}}
            <div class="flex flex-col items-center">
                <a href="{{ route('guest.kritik-saran') }}"
                   class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
                    <img src="{{ asset('images/icons/kritik-dan-saran.png') }}" alt="Kritik & Saran" class="w-12 h-12"/>
                </a>
                <span class="text-xs font-medium text-gray-700 text-center">{{ __('app.feedback_suggestion') }}</span>
            </div>

            {{-- Tanya AI --}}
            <div class="flex flex-col items-center">
                <a href="#"
                   class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3 relative">
                    <img src="{{ asset('images/icons/tanya-ai.png') }}" alt="Tanya AI" class="w-12 h-12"/>
                    <div
                        class="absolute -bottom-1 -right-1 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-lock text-gray-800 text-xs"></i>
                    </div>
                </a>
                <span class="text-xs font-medium text-gray-700 text-center">{{ __('app.ask_ai') }}</span>
            </div>

            {{-- Peninggalan Terdekat --}}
            <div class="flex flex-col items-center">
                <a href="#"
                   class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3 relative">
                    <img src="{{ asset('images/icons/peninggalan-terdekat.png') }}" alt="Peninggalan Terdekat"
                         class="w-12 h-12"/>
                    <div
                        class="absolute -bottom-1 -right-1 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-lock text-gray-800 text-xs"></i>
                    </div>
                </a>
                <span class="text-xs font-medium text-gray-700 text-center">{{ __('app.nearby_heritage') }}</span>
            </div>

            {{-- Game --}}
            <div class="flex flex-col items-center">
                <a href="#"
                   class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3 relative">
                    <img src="{{ asset('images/icons/game.png') }}" alt="Game" class="w-12 h-12"/>
                    <div
                        class="absolute -bottom-1 -right-1 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-lock text-gray-800 text-xs"></i>
                    </div>
                </a>
                <span class="text-xs font-medium text-gray-700 text-center">{{ __('app.game') }}</span>
            </div>
        </div>
    </div>

    {{-- Bottom Navigation --}}
    <x-bottom-nav/>

    {{-- Profile Completion Popup --}}
    @if(!auth()->user()->phone_number || !auth()->user()->address || !auth()->user()->date_of_birth)
        <div id="profile-reminder"
             x-data="{ show: !localStorage.getItem('profile_reminder_dismissed') || localStorage.getItem('profile_reminder_dismissed') !== '{{ date('Y-m-d') }}' }"
             x-show="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-user-edit text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Lengkapi Profil Anda
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Profil Anda belum lengkap. Harap lengkapi data berikut:
                                    </p>
                                    <ul class="list-disc list-inside mt-2 text-sm text-gray-500">
                                        @if(!auth()->user()->phone_number)
                                            <li>Nomor telepon</li>
                                        @endif
                                        @if(!auth()->user()->address)
                                            <li>Alamat</li>
                                        @endif
                                        @if(!auth()->user()->date_of_birth)
                                            <li>Tanggal lahir</li>
                                        @endif
                                        @if(!auth()->user()->pekerjaan)
                                            <li>Pekerjaan</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <a href="{{ route('profile.edit') }}"
                           class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                            Lengkapi Sekarang
                        </a>
                        <button type="button"
                                @click="show = false; localStorage.setItem('profile_reminder_dismissed', '{{ date('Y-m-d') }}')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Ingatkan Nanti
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-guest-layout>
