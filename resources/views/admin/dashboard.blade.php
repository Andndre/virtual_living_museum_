<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="mt-2 text-gray-600">Panel kontrol dan statistik sistem Virtual Living Museum</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.materi') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-book text-green-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Materi</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_materi'] }}</dd>
                                </dl>
                            </div>
                            <div class="ml-2">
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </a>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-red-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Situs</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_situs'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-flag text-yellow-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Laporan</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_laporan'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-comments text-purple-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Feedback</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_kritik_saran'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg mb-8">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users') }}" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-users mr-2"></i>
                            Kelola Pengguna
                        </a>
                        <a href="{{ route('admin.materi') }}" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                            <i class="fas fa-book mr-2"></i>
                            Kelola Materi
                        </a>
                        <a href="{{ route('admin.situs') }}" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Kelola Situs
                        </a>
                        <a href="{{ route('admin.reports') }}" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-flag mr-2"></i>
                            Kelola Laporan
                        </a>
                        <a href="{{ route('admin.feedback') }}" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors">
                            <i class="fas fa-comments mr-2"></i>
                            Kritik & Saran
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Users -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Pengguna Terbaru</h3>
                        <div class="flow-root">
                            <ul class="-my-5 divide-y divide-gray-200">
                                @forelse($recentUsers as $user)
                                <li class="py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($user->profile_photo)
                                                <img class="h-8 w-8 rounded-full" src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-500 text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                        </div>
                                        <div class="flex-shrink-0 text-sm text-gray-500">
                                            {{ $user->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="py-4 text-center text-gray-500">
                                    Belum ada pengguna terdaftar
                                </li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.users') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                Lihat Semua Pengguna
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Laporan Terbaru</h3>
                        <div class="flow-root">
                            <ul class="-my-5 divide-y divide-gray-200">
                                @forelse($recentReports as $report)
                                <li class="py-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-flag text-yellow-500"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">{{ $report->nama_peninggalan }}</p>
                                            <p class="text-sm text-gray-500">oleh {{ $report->user->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $report->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="py-4 text-center text-gray-500">
                                    Belum ada laporan
                                </li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.reports') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200">
                                Lihat Semua Laporan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Feedback -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Umpan Balik Terbaru</h3>
                        <div class="flow-root">
                            <ul class="-my-5 divide-y divide-gray-200">
                                @forelse($recentFeedback as $feedback)
                                <li class="py-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-comments text-purple-500"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900">{{ Str::limit($feedback->pesan, 100) }}</p>
                                            <p class="text-sm text-gray-500">Oleh {{ $feedback->user->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $feedback->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="py-4 text-center text-gray-500">
                                    Belum ada umpan balik
                                </li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.feedback') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200">
                                Lihat Semua Umpan Balik
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Sistem</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-sm text-gray-600">Total Penyimpanan yang Digunakan</span>
																<span class="text-sm font-medium text-gray-900">
																	{{ number_format(disk_free_space(storage_path()) / 1024 / 1024 / 1024, 2) }} GB tersedia dari {{ number_format(disk_total_space(storage_path()) / 1024 / 1024 / 1024, 2) }} GB
																</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-sm text-gray-600">Versi Laravel</span>
                                <span class="text-sm font-medium text-gray-900">{{ app()->version() }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-600">Versi PHP</span>
                                <span class="text-sm font-medium text-gray-900">{{ PHP_VERSION }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
