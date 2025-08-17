<x-elearning-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('guest.elearning') }}" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <a href="{{ route('profile.edit') }}" class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/30 hover:border-white/50 transition-colors">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Picture" class="w-full h-full object-cover" />
                @else
                    <img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="Profile Picture" class="w-full h-full object-cover" />
                @endif
            </a>
        </div>

        <div class="mb-8">
            <div class="flex items-center space-x-2 mb-2">
                <i class="fas fa-book text-yellow-400"></i>
                <span class="text-sm opacity-90">Materi</span>
            </div>
            <h1 class="text-2xl font-bold">{{ $materi->judul }}</h1>
        </div>

        {{-- Tab Navigation --}}
        <div class="grid grid-cols-4 gap-2 mb-4">
            {{-- Pre-test Tab --}}
            <button onclick="switchTab('pretest')" id="pretest-tab" class="tab-button flex flex-col items-center p-2 rounded-lg transition-colors">
                <div class="w-12 h-12 rounded-full border-2 border-white flex items-center justify-center mb-2 relative">
                    @if($pretest_completed)
                        <i class="fas fa-check text-white"></i>
                    @elseif(true) {{-- Pre-test is always available --}}
                        <i class="fas fa-file-alt text-white"></i>
                    @endif
                </div>
                <span class="text-xs text-center text-white">Pre test</span>
            </button>

            {{-- E-Book Tab --}}
            <button onclick="switchTab('ebook')" id="ebook-tab" class="tab-button flex flex-col items-center p-2 rounded-lg transition-colors {{ !$ebook_available ? 'opacity-50' : '' }}">
                <div class="w-12 h-12 rounded-full border-2 border-white flex items-center justify-center mb-2 relative">
                    @if(!$ebook_available)
                        <i class="fas fa-lock text-white"></i>
                    @elseif($all_ebooks_read)
                        <i class="fas fa-check text-white"></i>
                    @else
                        <i class="fas fa-book text-white"></i>
                    @endif
                </div>
                <span class="text-xs text-center text-white">E-Book</span>
            </button>

            {{-- Virtual Living Museum Tab --}}
            <button onclick="switchTab('museum')" id="museum-tab" class="tab-button flex flex-col items-center p-2 rounded-lg transition-colors {{ !$museum_available ? 'opacity-50' : '' }}">
                <div class="w-12 h-12 rounded-full border-2 border-white flex items-center justify-center mb-2 relative">
                    @if(!$museum_available)
                        <i class="fas fa-lock text-white"></i>
                    @elseif($all_museums_visited)
                        <i class="fas fa-check text-white"></i>
                    @else
                        <i class="fas fa-map-marker-alt text-white"></i>
                    @endif
                </div>
                <span class="text-xs text-center text-white">Virtual Museum</span>
            </button>

            {{-- Post-test Tab --}}
            <button onclick="switchTab('posttest')" id="posttest-tab" class="tab-button flex flex-col items-center p-2 rounded-lg transition-colors {{ !$posttest_available ? 'opacity-50' : '' }}">
                <div class="w-12 h-12 rounded-full border-2 border-white flex items-center justify-center mb-2 relative">
                    @if(!$posttest_available)
                        <i class="fas fa-lock text-white"></i>
                    @elseif($posttest_completed)
                        <i class="fas fa-check text-white"></i>
                    @else
                        <i class="fas fa-clipboard-check text-white"></i>
                    @endif
                </div>
                <span class="text-xs text-center text-white">Post test</span>
            </button>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="bg-white rounded-t-3xl -mt-6 relative min-h-screen">
        <div class="px-6 py-8">
            {{-- Pre Test Tab Content --}}
            <div id="pretest-content" class="tab-content">
                @if($materi->pretest->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                        <h2 class="text-xl font-bold text-center text-gray-900 mb-6">Pre Test</h2>
                        
                        <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                            <p class="text-center text-gray-700 leading-relaxed">
                                Anda akan mengerjakan Pre Test materi <strong>{{ $materi->judul }}</strong>. Siap mulai?
                            </p>
                        </div>

                        <div class="text-center">
                            @if($pretest_completed)
                                <a href="{{ route('guest.elearning.pretest', $materi->materi_id) }}" 
                                   class="inline-flex items-center px-8 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    Lihat Hasil
                                </a>
                            @else
                                <a href="{{ route('guest.elearning.pretest', $materi->materi_id) }}" 
                                   class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                    Mulai
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Panduan Mengisi Pre Test --}}
                    @if(!$pretest_completed)
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Panduan Mengisi Pre Test</h3>
                            
                            <div class="bg-yellow-50 rounded-2xl p-4 sm:p-6 border border-yellow-200">
                                {{-- Mobile Layout (Vertical Stack) --}}
                                <div class="sm:hidden">
                                    {{-- Mascot Section --}}
                                    <div class="flex justify-center mb-4">
                                        <div class="w-28 h-32 bg-yellow-100 rounded-xl flex items-center justify-center">
                                            <img src="{{ asset('images/mascot.png') }}" alt="Guide" class="w-24 h-24 object-contain">
                                        </div>
                                    </div>
                                    {{-- Content Section --}}
                                    <div class="space-y-3">
                                        <p class="text-gray-700 leading-relaxed text-sm">
                                            <span class="font-medium">Sebelum memulai</span> pembelajaran, Anda diminta untuk mengerjakan pretest sebagai evaluasi awal. 
                                            Pretest berisi <span class="font-medium">pertanyaan pilihan ganda</span>. Silakan pilih jawaban yang 
                                            <span class="font-medium">paling sesuai</span> pada setiap pertanyaan.
                                        </p>
                                        <p class="text-gray-700 leading-relaxed text-sm">
                                            Waktu pengerjaan <span class="font-medium">tidak dibatasi</span>, jadi pastikan Anda 
                                            membaca soal dengan teliti sebelum menjawab. Hasil pretest 
                                            <span class="font-medium">tidak mempengaruhi nilai akhir</span>, namun digunakan 
                                            untuk mengetahui pengertian awal Anda.
                                        </p>
                                    </div>
                                </div>

                                {{-- Desktop Layout (Side by Side) --}}
                                <div class="hidden sm:flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-20 bg-yellow-100 rounded-xl flex items-center justify-center">
                                            <img src="{{ asset('images/mascot.png') }}" alt="Guide" class="w-12 h-12 object-contain">
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-gray-700 leading-relaxed text-sm">
                                            <span class="font-medium">Sebelum memulai</span> pembelajaran, Anda diminta untuk mengerjakan pretest sebagai evaluasi awal. 
                                            Pretest berisi <span class="font-medium">pertanyaan pilihan ganda</span>. Silakan pilih jawaban yang 
                                            <span class="font-medium">paling sesuai</span> pada setiap pertanyaan.
                                        </p>
                                        <br>
                                        <p class="text-gray-700 leading-relaxed text-sm">
                                            Waktu pengerjaan <span class="font-medium">tidak dibatasi</span>, jadi pastikan Anda 
                                            membaca soal dengan teliti sebelum menjawab. Hasil pretest 
                                            <span class="font-medium">tidak mempengaruhi nilai akhir</span>, namun digunakan 
                                            untuk mengetahui pengertian awal Anda.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-100 rounded-2xl p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Pre Test Tidak Tersedia</h3>
                        <p class="text-gray-600">Materi ini tidak memiliki pre test</p>
                    </div>
                @endif
            </div>

            {{-- E-Book Tab Content --}}
            <div id="ebook-content" class="tab-content hidden">
                @if($materi->ebook->count() > 0)
                    <h2 class="text-xl font-bold text-center text-gray-900 mb-6">E-Book</h2>
                    
                    @if(!$ebook_available)
                        <div class="bg-gray-100 rounded-2xl p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-lock text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">E-Book Terkunci</h3>
                            <p class="text-gray-600">Selesaikan pre-test terlebih dahulu untuk mengakses e-book</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($materi->ebook as $ebook)
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                    {{-- E-book Preview --}}
                                    <div class="aspect-[3/4] bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                                        @if($ebook->path_file && file_exists(storage_path('app/public/' . $ebook->path_file)))
                                            <div class="text-center">
                                                <div class="w-20 h-24 mx-auto mb-4 bg-white rounded-lg shadow-lg flex items-center justify-center border-2 border-gray-200">
                                                    <i class="fas fa-file-pdf text-red-500 text-3xl"></i>
                                                </div>
                                                <h3 class="font-medium text-gray-900">{{ $ebook->judul }}</h3>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <div class="w-20 h-24 mx-auto mb-4 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-exclamation-triangle text-gray-400 text-2xl"></i>
                                                </div>
                                                <p class="text-gray-500">File tidak ditemukan</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- E-book Actions --}}
                                    <div class="p-4">
                                        @if($ebook->path_file && file_exists(storage_path('app/public/' . $ebook->path_file)))
                                            <a href="{{ route('guest.elearning.ebook', $ebook->ebook_id) }}" 
                                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                                <i class="fas fa-book-open mr-2"></i>
                                                Baca E-Book
                                            </a>
                                        @else
                                            <div class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-400 text-white font-medium rounded-xl">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                File Error
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="bg-gray-100 rounded-2xl p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-book text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">E-Book Tidak Tersedia</h3>
                        <p class="text-gray-600">Materi ini tidak memiliki e-book</p>
                    </div>
                @endif
            </div>

            {{-- Virtual Living Museum Tab Content --}}
            <div id="museum-content" class="tab-content hidden">
                @if($materi->situsPeninggalan->count() > 0)
                    <h2 class="text-xl font-bold text-center text-gray-900 mb-6">Virtual Living Museum</h2>
                    
                    @if(!$museum_available)
                        <div class="bg-gray-100 rounded-2xl p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-lock text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Virtual Museum Terkunci</h3>
                            <p class="text-gray-600">Baca semua e-book terlebih dahulu untuk mengakses virtual museum</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($materi->situsPeninggalan as $situs)
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                    <div class="flex">
                                        {{-- Situs Image --}}
                                        <div class="w-24 h-24 flex-shrink-0">
                                            <div class="w-full h-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                                                <i class="fas fa-map-marker-alt text-white text-xl"></i>
                                            </div>
                                        </div>
                                        
                                        {{-- Situs Content --}}
                                        <div class="flex-1 p-4">
                                            <h3 class="font-semibold text-gray-900 mb-1">{{ $situs->nama }}</h3>
                                            <p class="text-sm text-gray-600 mb-3">{{ $situs->alamat }}</p>
                                            
                                            <a href="{{ route('guest.situs.detail', $situs->situs_id) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-orange-600 text-white text-xs font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>
                                                Kunjungi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="bg-gray-100 rounded-2xl p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Virtual Museum Tidak Tersedia</h3>
                        <p class="text-gray-600">Materi ini tidak memiliki virtual museum</p>
                    </div>
                @endif
            </div>

            {{-- Post Test Tab Content --}}
            <div id="posttest-content" class="tab-content hidden">
                @if($materi->posttest->count() > 0)
                    <h2 class="text-xl font-bold text-center text-gray-900 mb-6">Post Test</h2>
                    
                    @if(!$posttest_available)
                        <div class="bg-gray-100 rounded-2xl p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-lock text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Post Test Terkunci</h3>
                            <p class="text-gray-600">Kunjungi semua virtual museum terlebih dahulu untuk mengakses post-test</p>
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                                <p class="text-center text-gray-700 leading-relaxed">
                                    Anda akan mengerjakan Post Test materi <strong>{{ $materi->judul }}</strong>. Siap mulai?
                                </p>
                            </div>

                            <div class="text-center">
                                @if($posttest_completed)
                                    <a href="{{ route('guest.elearning.posttest', $materi->materi_id) }}" 
                                       class="inline-flex items-center px-8 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                                        <i class="fas fa-eye mr-2"></i>
                                        Lihat Hasil
                                    </a>
                                @else
                                    <a href="{{ route('guest.elearning.posttest', $materi->materi_id) }}" 
                                       class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                        Mulai
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-100 rounded-2xl p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Post Test Tidak Tersedia</h3>
                        <p class="text-gray-600">Materi ini tidak memiliki post test</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tab JavaScript --}}
    <script>
        function switchTab(tabName) {
            // Check if tab is available
            const tabButton = document.getElementById(tabName + '-tab');
            if (tabButton.classList.contains('opacity-50')) {
                return; // Don't switch if tab is locked
            }

            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('bg-white/20');
            });

            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Add active class to selected tab button
            document.getElementById(tabName + '-tab').classList.add('bg-white/20');
        }

        // Initialize first available tab
        document.addEventListener('DOMContentLoaded', function() {
            // Always start with pretest tab
            switchTab('pretest');
        });
    </script>
</x-elearning-layout>
