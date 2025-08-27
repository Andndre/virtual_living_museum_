<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Riwayat Pengembangan</h1>
                        <p class="mt-2 text-sm sm:text-base text-gray-600">Kelola riwayat pengembangan aplikasi</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.riwayat-pengembang.create') }}"
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            <span class="hidden sm:inline">Tambah Riwayat</span>
                            <span class="sm:hidden">Tambah</span>
                        </a>
                    </div>
                </div>
            </div>

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

            <!-- Content -->
            @if($riwayatPengembang->count() > 0)
                <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Riwayat Pengembangan</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Daftar riwayat pengembangan aplikasi
                                </p>
                            </div>
                            <div class="mt-3 sm:mt-0 text-sm text-gray-500">
                                Total: {{ $riwayatPengembang->count() }} riwayat
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Judul
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tahun Mulai
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tahun Selesai
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($riwayatPengembang as $riwayat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $riwayat->judul }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($riwayat->tahun)->format('d F Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $riwayat->tahun_selesai ? \Carbon\Carbon::parse($riwayat->tahun_selesai)->format('d F Y') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.riwayat-pengembang.edit', $riwayat->id) }}"
                                           class="text-blue-600 hover:text-blue-900 mr-3"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.riwayat-pengembang.destroy', $riwayat->id) }}"
                                              method="POST"
                                              class="inline-block"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?')">
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
                            @foreach($riwayatPengembang as $riwayat)
                            <div class="p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base font-medium text-gray-900">{{ $riwayat->judul }}</h4>
                                        <div class="mt-1 text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <i class="far fa-calendar-alt mr-2"></i>
                                                {{ \Carbon\Carbon::parse($riwayat->tahun)->format('d M Y') }} -
                                                {{ $riwayat->tahun_selesai ? \Carbon\Carbon::parse($riwayat->tahun_selesai)->format('d M Y') : 'Sekarang' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex space-x-2">
                                        <a href="{{ route('admin.riwayat-pengembang.edit', $riwayat->id) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.riwayat-pengembang.destroy', $riwayat->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($riwayatPengembang->hasPages())
                        <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                            {{ $riwayatPengembang->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white shadow-lg sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-12 text-center">
                        <div class="mx-auto h-20 w-20 sm:h-24 sm:w-24 text-gray-400">
                            <i class="fas fa-history text-4xl sm:text-5xl"></i>
                        </div>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Belum ada riwayat pengembangan</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Mulai dengan menambahkan riwayat pengembangan baru.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('admin.riwayat-pengembang.create') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus -ml-1 mr-2"></i>
                                Tambah Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
