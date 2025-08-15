<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Laporan Peninggalan</h1>
                        <p class="mt-2 text-sm sm:text-base text-gray-600">Kelola dan review laporan peninggalan dari pengguna</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span class="hidden sm:inline">Kembali ke Dashboard</span>
                            <span class="sm:hidden">Dashboard</span>
                        </a>
                    </div>
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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-alt text-xl sm:text-2xl text-blue-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Laporan</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $reports->total() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-images text-xl sm:text-2xl text-green-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Gambar</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $reports->sum(function($r) { return $r->laporanGambar->count(); }) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-xl sm:text-2xl text-orange-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Hari Ini</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $reports->where('created_at', '>=', now()->startOfDay())->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-xl sm:text-2xl text-purple-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Pelapor Aktif</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $reports->pluck('user_id')->unique()->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            @if($reports->count() > 0)
                <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Laporan Peninggalan</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Review dan kelola laporan peninggalan dari pengguna
                                </p>
                            </div>
                            <div class="mt-3 sm:mt-0 text-sm text-gray-500">
                                Total: {{ $reports->total() }} laporan
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop Table View -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Laporan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lokasi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pelapor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Gambar
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $report->nama_peninggalan }}</div>
                                        <div class="text-sm text-gray-500 max-w-xs">{{ Str::limit($report->deskripsi, 80) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs">{{ Str::limit($report->alamat, 40) }}</div>
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-map-pin mr-1"></i>
                                            {{ $report->lat }}, {{ $report->lng }}
                                        </div>
                                        <div class="text-xs">
                                            <a href="https://www.google.com/maps?q={{ $report->lat }},{{ $report->lng }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-external-link-alt mr-1"></i>Lihat di Peta
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($report->user && $report->user->profile_photo)
                                                <img class="h-8 w-8 rounded-full mr-3" src="{{ asset('storage/' . $report->user->profile_photo) }}" alt="Profile">
                                            @else
                                                <div class="h-8 w-8 bg-gray-300 rounded-full mr-3 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-500 text-xs"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $report->user->name ?? 'User Tidak Ditemukan' }}</div>
                                                <div class="text-xs text-gray-500">{{ $report->user->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($report->laporanGambar->count() > 0)
                                            <div class="flex items-center justify-center">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $report->laporanGambar->count() }} foto
                                                </span>
                                            </div>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Tanpa foto
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $report->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $report->created_at->format('H:i') }}</div>
                                        <div class="text-xs text-gray-400">{{ $report->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.reports.show', $report->laporan_id) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="mailto:{{ $report->user->email ?? '' }}?subject=Re: Laporan {{ $report->nama_peninggalan }}" class="text-green-600 hover:text-green-900" title="Kontak Pelapor">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                        <form action="{{ route('admin.reports.destroy', $report->laporan_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="lg:hidden">
                        <div class="divide-y divide-gray-200">
                            @foreach($reports as $report)
                            <div class="p-4">
                                <div class="flex items-start space-x-3">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                                            <i class="fas fa-file-alt text-white text-sm"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Name and Description -->
                                        <div class="mb-2">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $report->nama_peninggalan }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($report->deskripsi, 100) }}</p>
                                        </div>
                                        
                                        <!-- Location -->
                                        <div class="mb-2">
                                            <p class="text-xs text-gray-600">
                                                <i class="fas fa-map-pin mr-1"></i>
                                                {{ Str::limit($report->alamat, 50) }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <a href="https://www.google.com/maps?q={{ $report->lat }},{{ $report->lng }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt mr-1"></i>{{ $report->lat }}, {{ $report->lng }}
                                                </a>
                                            </p>
                                        </div>
                                        
                                        <!-- Status Info -->
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            @if($report->laporanGambar->count() > 0)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $report->laporanGambar->count() }} foto
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Tanpa foto
                                                </span>
                                            @endif
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $report->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        
                                        <!-- Reporter Info -->
                                        <div class="flex items-center mb-3">
                                            @if($report->user && $report->user->profile_photo)
                                                <img class="h-6 w-6 rounded-full mr-2" src="{{ asset('storage/' . $report->user->profile_photo) }}" alt="Profile">
                                            @else
                                                <div class="h-6 w-6 bg-gray-300 rounded-full mr-2 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-500 text-xs"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-xs font-medium text-gray-900">{{ $report->user->name ?? 'User Tidak Ditemukan' }}</div>
                                                <div class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        
                                        <!-- Mobile Actions -->
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.reports.show', $report->laporan_id) }}" 
                                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>
                                                Detail
                                            </a>
                                            <a href="mailto:{{ $report->user->email ?? '' }}?subject=Re: Laporan {{ $report->nama_peninggalan }}" 
                                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-green-300 shadow-sm text-xs font-medium rounded text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                                <i class="fas fa-envelope mr-1"></i>
                                                Kontak
                                            </a>
                                            <form action="{{ route('admin.reports.destroy', $report->laporan_id) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($reports->hasPages())
                        <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white shadow-lg sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-12 text-center">
                        <div class="mx-auto h-20 w-20 sm:h-24 sm:w-24 text-gray-400">
                            <i class="fas fa-file-alt text-5xl sm:text-6xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Laporan</h3>
                        <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto px-4">
                            Belum ada laporan peninggalan dari pengguna. Laporan akan muncul di sini ketika pengguna melaporkan penemuan peninggalan baru.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
