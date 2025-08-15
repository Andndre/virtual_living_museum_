<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                                <a href="{{ route('admin.reports') }}" class="text-gray-400 hover:text-gray-500">Kelola Laporan</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                                <span class="text-gray-900 font-medium">{{ Str::limit($report->nama_peninggalan, 30) }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <div class="mb-4">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Detail Laporan Peninggalan</h1>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">Informasi lengkap laporan: <strong>{{ $report->nama_peninggalan }}</strong></p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-3">
                    @if($report->user)
                    <a href="mailto:{{ $report->user->email }}?subject=Re: Laporan {{ $report->nama_peninggalan }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        <span class="hidden sm:inline">Kontak Pelapor</span>
                        <span class="sm:hidden">Kontak</span>
                    </a>
                    @endif
                    <a href="{{ route('admin.reports') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="hidden sm:inline">Kembali ke Laporan</span>
                        <span class="sm:hidden">Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Basic Info Card -->
                    <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Peninggalan</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Peninggalan</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $report->nama_peninggalan }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $report->deskripsi }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alamat Lokasi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $report->alamat }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Koordinat GPS</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <div class="flex items-center space-x-4">
                                            <span><strong>Lat:</strong> {{ $report->lat }}</span>
                                            <span><strong>Lng:</strong> {{ $report->lng }}</span>
                                            <a href="https://www.google.com/maps?q={{ $report->lat }},{{ $report->lng }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-external-link-alt mr-1"></i>Lihat di Peta
                                            </a>
                                        </div>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Photos Gallery -->
                    @if($report->laporanGambar->count() > 0)
                    <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <i class="fas fa-images text-blue-600 mr-2"></i>
                                    Foto Peninggalan
                                </h3>
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ $report->laporanGambar->count() }} foto
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($report->laporanGambar as $index => $gambar)
                                <div class="relative group">
                                    <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $gambar->path_gambar) }}" 
                                             alt="Gambar {{ $index + 1 }}" 
                                             class="w-full h-48 object-cover group-hover:opacity-75 transition-opacity cursor-pointer"
                                             onclick="openModal('{{ asset('storage/' . $gambar->path_gambar) }}', 'Gambar {{ $index + 1 }}')">
                                    </div>
                                    <div class="absolute top-2 left-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-black bg-opacity-50 text-white">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Map Section -->
                    <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-map-marked-alt text-green-600 mr-2"></i>
                                Lokasi di Peta
                            </h3>
                            <div class="bg-gray-100 rounded-lg p-8 text-center">
                                <i class="fas fa-map text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600 mb-4">Koordinat: {{ $report->lat }}, {{ $report->lng }}</p>
                                <div class="space-y-2">
                                    <a href="https://www.google.com/maps?q={{ $report->lat }},{{ $report->lng }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Buka di Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Reporter Info -->
                    <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pelapor</h3>
                            @if($report->user)
                            <div class="flex items-center space-x-3 mb-4">
                                @if($report->user->profile_photo)
                                    <img class="h-12 w-12 rounded-full" src="{{ asset('storage/' . $report->user->profile_photo) }}" alt="Profile">
                                @else
                                    <div class="h-12 w-12 bg-gray-300 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $report->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $report->user->email }}</div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <a href="mailto:{{ $report->user->email }}?subject=Re: Laporan {{ $report->nama_peninggalan }}" class="w-full inline-flex items-center justify-center px-3 py-2 border border-green-300 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Kirim Email
                                </a>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-times text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">User tidak ditemukan atau telah dihapus</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Report Metadata -->
                    <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Laporan</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID Laporan</dt>
                                    <dd class="text-sm font-mono text-gray-900">#{{ $report->laporan_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                    <dd class="text-sm text-gray-900">{{ $report->created_at->format('d F Y, H:i') }}</dd>
                                    <dd class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jumlah Foto</dt>
                                    <dd class="text-sm text-gray-900">{{ $report->laporanGambar->count() }} foto</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Action Panel -->
                    <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Laporan</h3>
                            <div class="space-y-3">
                                <a href="https://www.google.com/maps?q={{ $report->lat }},{{ $report->lng }}" target="_blank" class="w-full inline-flex items-center justify-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-map-marked-alt mr-2"></i>
                                    Lihat Lokasi
                                </a>
                                
                                <hr class="my-4">
                                
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-red-800 mb-2">Zona Bahaya</h4>
                                    <p class="text-xs text-red-600 mb-3">Tindakan ini akan menghapus laporan beserta semua foto yang terkait dan tidak dapat dibatalkan.</p>
                                    <form action="{{ route('admin.reports.destroy', $report->laporan_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan &quot;{{ $report->nama_peninggalan }}&quot;? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                            <i class="fas fa-trash mr-2"></i>
                                            Hapus Laporan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 p-4" style="display: none;">
        <div class="flex items-center justify-center w-full h-full">
            <div class="relative max-w-4xl max-h-full">
                <button onclick="closeModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 text-3xl font-bold z-10">
                    &times;
                </button>
                <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
                <div class="absolute bottom-4 left-4 right-4 text-center">
                    <p id="modalCaption" class="text-white text-sm bg-black bg-opacity-50 rounded px-3 py-2 inline-block"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(imageSrc, caption) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalCaption').textContent = caption;
            const modal = document.getElementById('imageModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</x-app-layout>
