<x-app-layout>
    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">Kelola Video Peninggalan</h1>
                <a href="{{ route('admin.video-peninggalan.create') }}"
                    class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Tambah Video
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    @if ($data->count())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Thumbnail</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Judul</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Deskripsi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Link</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($data as $index => $video)
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ $index + 1 }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="Thumbnail"
                                                class="h-16 w-28 rounded object-cover">
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                            {{ $video->judul }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ Str::limit($video->deskripsi, 100) }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            @if ($video->link)
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-video text-green-600"></i>
                                                    <a href="{{ asset('storage/' . $video->link) }}" target="_blank"
                                                        class="text-blue-600 hover:underline">
                                                        Tonton Video
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-gray-500">Tidak ada video</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('admin.video-peninggalan.edit', $video->id) }}"
                                                    class="inline-flex items-center rounded-md border border-transparent bg-yellow-600 px-3 py-1 text-sm font-medium leading-4 text-white hover:bg-yellow-700">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>
                                                <form
                                                    action="{{ route('admin.video-peninggalan.destroy', $video->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Yakin hapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-3 py-1 text-sm font-medium leading-4 text-white hover:bg-red-700">
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
                        <div class="p-12 text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                                <i class="fas fa-video text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="mb-2 text-lg font-medium text-gray-900">Belum Ada Video</h3>
                            <p class="mb-4 text-gray-600">Tambahkan video peninggalan pertama.</p>
                            <a href="{{ route('admin.video-peninggalan.create') }}"
                                class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Tambah Video
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
