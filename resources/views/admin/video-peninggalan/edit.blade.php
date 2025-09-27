<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Edit Video Peninggalan</h1>
                <p class="mt-2 text-sm text-gray-600">Ubah informasi video peninggalan</p>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('admin.video-peninggalan.update', $video->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                                <input type="text" name="judul" id="judul"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('judul', $video->judul) }}" required>
                            </div>

                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                          required>{{ old('deskripsi', $video->deskripsi) }}</textarea>
                            </div>

                            <div>
                                <label for="link" class="block text-sm font-medium text-gray-700">File Video</label>
                                <input type="file" name="link" id="link"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                                       accept="video/mp4,video/mov,video/avi,video/wmv,video/flv,video/webm">
                                <p class="mt-2 text-sm text-gray-500">Upload file video baru untuk mengganti video saat ini (opsional)</p>

                                @if($video->link)
                                    <div class="mt-3">
                                        <p class="text-sm font-medium text-gray-700">Video saat ini:</p>
                                        <div class="mt-2 flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-video text-green-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">{{ basename($video->link) }}</p>
                                                <video controls class="mt-2 h-32 w-auto rounded-lg border border-gray-300">
                                                    <source src="{{ asset('storage/' . $video->link) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail</label>
                                <input type="file" name="thumbnail" id="thumbnail"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       accept="image/*">
                                <p class="mt-2 text-sm text-gray-500">Upload gambar baru untuk mengganti thumbnail (opsional)</p>

                                @if($video->thumbnail)
                                    <div class="mt-3">
                                        <p class="text-sm font-medium text-gray-700">Thumbnail saat ini:</p>
                                        <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="Current thumbnail"
                                             class="mt-2 h-32 w-auto object-cover rounded-lg border border-gray-300">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.video-peninggalan.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
