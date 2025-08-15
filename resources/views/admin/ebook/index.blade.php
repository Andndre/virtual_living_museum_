<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola E-book</h1>
                        <p class="mt-2 text-sm sm:text-base text-gray-600">Materi: <strong>{{ $materi->judul }}</strong></p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.ebook.create', $materi->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            <span class="hidden sm:inline">Tambah E-book</span>
                            <span class="sm:hidden">Tambah</span>
                        </a>
                        <a href="{{ route('admin.materi.show', $materi->materi_id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span class="hidden sm:inline">Kembali ke Materi</span>
                            <span class="sm:hidden">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Daftar E-book
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Total: {{ $ebooks->total() }} e-book
                        </p>
                    </div>

                    @if($ebooks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Judul E-book
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            File
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($ebooks as $index => $ebook)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $ebooks->firstItem() + $index }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 bg-red-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $ebook->judul }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($ebook->path_file && file_exists(storage_path('app/public/' . $ebook->path_file)))
                                                    <div class="flex items-center">
                                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                                        <a href="{{ asset('storage/' . $ebook->path_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                            {{ basename($ebook->path_file) }}
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="flex items-center">
                                                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                                        <span class="text-red-600">File tidak ditemukan</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex space-x-2 justify-end">
                                                    @if($ebook->path_file && file_exists(storage_path('app/public/' . $ebook->path_file)))
                                                        <a href="{{ asset('storage/' . $ebook->path_file) }}" target="_blank"
                                                           class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                            <i class="fas fa-eye mr-1"></i>
                                                            Lihat
                                                        </a>
                                                    @endif
                                                    
                                                    <a href="{{ route('admin.ebook.edit', [$materi->materi_id, $ebook->ebook_id]) }}"
                                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Edit
                                                    </a>
                                                    
                                                    <form method="POST" action="{{ route('admin.ebook.destroy', $ebook->ebook_id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus e-book ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                            <i class="fas fa-trash mr-1"></i>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($ebooks->hasPages())
                            <div class="mt-6">
                                {{ $ebooks->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-book text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada e-book</h3>
                            <p class="text-sm text-gray-500 mb-6">
                                Mulai dengan menambahkan e-book pertama untuk materi ini.
                            </p>
                            <a href="{{ route('admin.ebook.create', $materi->materi_id) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah E-book
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
