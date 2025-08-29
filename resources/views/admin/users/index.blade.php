<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Pengguna</h1>
                        <p class="mt-2 text-sm sm:text-base text-gray-600">Kelola semua pengguna yang terdaftar di
                            sistem</p>
                    </div>
                    <div class="flex">
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span class="hidden sm:inline">Kembali ke Dashboard</span>
                            <span class="sm:hidden">Kembali</span>
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

            <!-- Users Table/Cards -->
            <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg border border-gray-200">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Pengguna</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Kelola dan pantau semua pengguna yang terdaftar
                            </p>
                        </div>
                        <div class="mt-3 sm:mt-0 text-sm text-gray-500">
                            Total: {{ $users->count() }} pengguna
                        </div>
                    </div>
                </div>

                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengguna
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Peran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Bergabung
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aktivitas Terakhir
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($user->profile_photo)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                     src="{{ asset('storage/' . $user->profile_photo) }}"
                                                     alt="{{ $user->name }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-500"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d M Y') }}
                                    <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($user->logAktivitas->count() > 0)
                                        {{ $user->logAktivitas->first()->created_at->diffForHumans() }}
                                    @else
                                        <span class="text-gray-400">Tidak ada aktivitas</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                           class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                    <p>Tidak ada pengguna ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden">
                    @forelse($users as $user)
                        <div class="p-4 border-b border-gray-200 last:border-b-0">
                            <div class="flex items-start space-x-3">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    @if($user->profile_photo)
                                        <img class="h-12 w-12 rounded-full object-cover"
                                             src="{{ asset('storage/' . $user->profile_photo) }}"
                                             alt="{{ $user->name }}">
                                    @else
                                        <div
                                            class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Name and Email -->
                                    <div class="mb-2">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</h4>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                    </div>

                                    <!-- Status Badges -->
                                    <div class="flex flex-wrap gap-2 mb-3">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                        @if($user->email_verified_at)
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Terverifikasi
                                        </span>
                                        @else
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Belum Terverifikasi
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Meta Info -->
                                    <div class="space-y-1 text-xs text-gray-500 mb-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                            Bergabung {{ $user->created_at->format('d M Y') }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-2 text-gray-400"></i>
                                            @if($user->logAktivitas->count() > 0)
                                                Aktif {{ $user->logAktivitas->first()->created_at->diffForHumans() }}
                                            @else
                                                Tidak ada aktivitas
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detail
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-blue-300 shadow-sm text-xs font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                  class="flex-1"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="mx-auto h-16 w-16 text-gray-400 mb-4">
                                <i class="fas fa-users text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pengguna</h3>
                            <p class="text-sm text-gray-500">Belum ada pengguna yang terdaftar di sistem</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
