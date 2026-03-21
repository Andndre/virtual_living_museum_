<x-app-layout>
    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="mt-2 text-gray-600">Panel kontrol dan statistik sistem {{ config('app.name') }}</p>
            </div>

            <!-- Statistics Cards -->
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-5">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-2xl text-blue-600"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Pengguna</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.materi') }}"
                    class="overflow-hidden rounded-lg bg-white shadow transition-shadow hover:shadow-md">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-book text-2xl text-green-600"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Materi</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_materi'] }}</dd>
                                </dl>
                            </div>
                            <div class="ml-2">
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </a>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-2xl text-red-600"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Situs</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_situs'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-flag text-2xl text-yellow-600"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Laporan</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_laporan'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-comments text-2xl text-purple-600"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Feedback</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_kritik_saran'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8 rounded-lg bg-white shadow">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="mb-4 text-lg font-medium leading-6 text-gray-900">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        <a href="{{ route('admin.users') }}"
                            class="flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                            <i class="fas fa-users mr-2"></i>
                            Kelola Pengguna
                        </a>
                        <a href="{{ route('admin.materi') }}"
                            class="flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700">
                            <i class="fas fa-book mr-2"></i>
                            Kelola Materi
                        </a>
                        <a href="{{ route('admin.situs') }}"
                            class="flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Kelola Situs
                        </a>
                        <a href="{{ route('admin.reports') }}"
                            class="flex items-center justify-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-yellow-700">
                            <i class="fas fa-flag mr-2"></i>
                            Kelola Laporan
                        </a>
                        <a href="{{ route('admin.feedback') }}"
                            class="flex items-center justify-center rounded-md border border-transparent bg-purple-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-purple-700">
                            <i class="fas fa-comments mr-2"></i>
                            Kritik & Saran
                        </a>
                        <a href="{{ route('admin.video-peninggalan.index') }}"
                            class="flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                            <i class="fas fa-video mr-2"></i>
                            Video Peninggalan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Recent Users -->
                <div class="rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="mb-4 text-lg font-medium leading-6 text-gray-900">Pengguna Terbaru</h3>
                        <div class="flow-root">
                            <ul class="-my-5 divide-y divide-gray-200">
                                @forelse($recentUsers as $user)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                @if ($user->profile_photo)
                                                    <img class="h-8 w-8 rounded-full"
                                                        src="{{ asset('storage/' . $user->profile_photo) }}"
                                                        alt="{{ $user->name }}">
                                                @else
                                                    <div
                                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-300">
                                                        <i class="fas fa-user text-sm text-gray-500"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-medium text-gray-900">
                                                    {{ $user->name }}</p>
                                                <p class="truncate text-sm text-gray-500">{{ $user->email }}</p>
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
                            <a href="{{ route('admin.users') }}"
                                class="flex w-full items-center justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-200">
                                Lihat Semua Pengguna
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="mb-4 text-lg font-medium leading-6 text-gray-900">Laporan Terbaru</h3>
                        <div class="flow-root">
                            <ul class="-my-5 divide-y divide-gray-200">
                                @forelse($recentReports as $report)
                                    <li class="py-4">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-flag text-yellow-500"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $report->nama_peninggalan }}</p>
                                                <p class="text-sm text-gray-500">oleh {{ $report->user->name }}</p>
                                                <p class="text-xs text-gray-400">
                                                    {{ $report->created_at->diffForHumans() }}</p>
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
                            <a href="{{ route('admin.reports') }}"
                                class="flex w-full items-center justify-center rounded-md border border-transparent bg-yellow-100 px-4 py-2 text-sm font-medium text-yellow-700 hover:bg-yellow-200">
                                Lihat Semua Laporan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Feedback -->
                <div class="rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="mb-4 text-lg font-medium leading-6 text-gray-900">Umpan Balik Terbaru</h3>
                        <div class="flow-root">
                            <ul class="-my-5 divide-y divide-gray-200">
                                @forelse($recentFeedback as $feedback)
                                    <li class="py-4">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-comments text-purple-500"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm text-gray-900">
                                                    {{ Str::limit($feedback->pesan, 100) }}</p>
                                                <p class="text-sm text-gray-500">Oleh {{ $feedback->user->name }}</p>
                                                <p class="text-xs text-gray-400">
                                                    {{ $feedback->created_at->diffForHumans() }}</p>
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
                            <a href="{{ route('admin.feedback') }}"
                                class="flex w-full items-center justify-center rounded-md border border-transparent bg-purple-100 px-4 py-2 text-sm font-medium text-purple-700 hover:bg-purple-200">
                                Lihat Semua Umpan Balik
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="mb-4 text-lg font-medium leading-6 text-gray-900">Informasi Sistem</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between border-b border-gray-200 py-2">
                                <span class="text-sm text-gray-600">Total Penyimpanan yang Digunakan</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ number_format(disk_free_space(storage_path()) / 1024 / 1024 / 1024, 2) }} GB
                                    tersedia dari
                                    {{ number_format(disk_total_space(storage_path()) / 1024 / 1024 / 1024, 2) }} GB
                                </span>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-200 py-2">
                                <span class="text-sm text-gray-600">Versi Laravel</span>
                                <span class="text-sm font-medium text-gray-900">{{ app()->version() }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
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
