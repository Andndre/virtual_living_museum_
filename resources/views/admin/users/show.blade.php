<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="mb-4">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Detail Pengguna</h1>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">Melihat pengguna: {{ $user->name }}</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        <span class="hidden sm:inline">Edit Pengguna</span>
                        <span class="sm:hidden">Edit</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="hidden sm:inline">Kembali ke Pengguna</span>
                        <span class="sm:hidden">Kembali</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- User Profile -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6 text-center">
                            <div class="mb-4">
                                @if($user->profile_photo)
                                    <img class="h-32 w-32 rounded-full object-cover mx-auto" src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                                @else
                                    <div class="h-32 w-32 rounded-full bg-gray-300 flex items-center justify-center mx-auto">
                                        <i class="fas fa-user text-gray-500 text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <div class="mt-2">
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="bg-white shadow rounded-lg mt-6">
                        <div class="px-4 py-5 sm:p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Sistem</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Bergabung</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->updated_at->format('d M Y, H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email Terverifikasi</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($user->email_verified_at)
                                            {{ $user->email_verified_at->format('d M Y, H:i') }}
                                        @else
                                            <span class="text-red-600">Belum terverifikasi</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Bergabung</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Activity & Statistics -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white shadow rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clipboard-check text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Kuis Dikerjakan</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $user->jawabanUser->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Situs Diakses</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $user->aksesSitusUser->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-comments text-purple-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Feedback Diberikan</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $user->kritikSaran->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-flag text-red-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Laporan Dibuat</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $user->laporanPeninggalan->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Aktivitas Terbaru</h4>
                            @if($user->logAktivitas->count() > 0)
                                <div class="flow-root">
                                    <ul class="-my-5 divide-y divide-gray-200">
                                        @foreach($user->logAktivitas->take(10) as $activity)
                                        <li class="py-4">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-circle text-blue-400 text-xs mt-2"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-gray-900">{{ $activity->aktivitas }}</p>
                                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Tidak ada aktivitas tercatat</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Reports (if any) -->
                    @if($user->laporanPeninggalan->count() > 0)
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Laporan Terbaru</h4>
                            <div class="space-y-4">
                                @foreach($user->laporanPeninggalan->take(5) as $report)
                                <div class="border-l-4 border-yellow-400 pl-4">
                                    <div class="flex justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $report->nama_peninggalan }}</p>
                                            <p class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</p>
                                        </div>
                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Menunggu</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
