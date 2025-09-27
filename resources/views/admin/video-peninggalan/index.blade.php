<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Kelola Video Peninggalan</h1>
                <a href="{{ route('admin.video-peninggalan.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Tambah Video
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    @if($data->count())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thumbnail</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($data as $index => $video)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="Thumbnail" class="h-16 w-28 object-cover rounded">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $video->judul }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($video->deskripsi, 100) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($video->link)
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-video text-green-600"></i>
                                                    <a href="{{ asset('storage/' . $video->link) }}" target="_blank" class="text-blue-600 hover:underline">
                                                        Tonton Video
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-gray-500">Tidak ada video</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex space-x-2 justify-end">
                                                <a href="{{ route('admin.video-peninggalan.edit', $video->id) }}"
                                                   class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>
                                                <form action="{{ route('admin.video-peninggalan.destroy', $video->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                                        <i class="fas fa-trash mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-video text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada video peninggalan</h3>
                            <p class="text-sm text-gray-500 mb-6">
                                Tambahkan video peninggalan pertama.
                            </p>
                            <a href="{{ route('admin.video-peninggalan.create') }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Tambah Video
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
