<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Tugas</h1>
                        <p class="mt-2 text-sm sm:text-base text-gray-600">Materi: {{ $materi->judul }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.tugas.create', $materi->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            <span>Tambah Tugas</span>
                        </a>
                        <a href="{{ route('admin.materi.show', $materi->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span>Kembali ke Detail Materi</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white shadow rounded-lg">
                @if($tugas->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tugas as $t)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $t->judul }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ Str::limit($t->deskripsi, 100) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($t->gambar)
                                                <a href="{{ asset('storage/' . $t->gambar) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                    <img src="{{ asset('storage/' . $t->gambar) }}" alt="{{ $t->judul }}" class="h-10 w-10 object-cover rounded">
                                                </a>
                                            @else
                                                <span class="text-gray-400">Tidak ada gambar</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.tugas.edit', [$materi->materi_id, $t->tugas_id]) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.tugas.destroy', [$materi->materi_id, $t->tugas_id]) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-8 px-4 text-center">
                        <i class="fas fa-clipboard-check text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Tugas</h3>
                        <p class="text-sm text-gray-500 mb-4">Belum ada tugas yang ditambahkan untuk materi ini.</p>
                        <a href="{{ route('admin.tugas.create', $materi->materi_id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Tugas Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
