<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Katalog</h1>
                <p class="mt-2 text-sm sm:text-base text-gray-600">Unggah atau perbarui file katalog PDF</p>
            </div>

            <!-- Alert Success -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-md">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                    <h3 class="text-sm font-medium text-red-800">Ada beberapa kesalahan:</h3>
                    <ul class="mt-2 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white shadow sm:rounded-lg">
                <form method="POST" action="{{ route('admin.katalog.update') }}" enctype="multipart/form-data"
                      id="katalogForm">
                    @csrf
                    @method('PUT')

                    <div class="px-4 py-5 sm:p-6 space-y-6">

                        <!-- Current File Info -->
                        @if($katalog && $katalog->path_pdf)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    File PDF Saat Ini
                                </label>
                                <div class="mt-1 flex items-center p-3 bg-gray-50 rounded-lg border">
                                    <i class="fas fa-file-pdf text-red-600 text-2xl"></i>
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ basename($katalog->path_pdf) }}
                                        </span>
                                        @if(Storage::disk('public')->exists($katalog->path_pdf))
                                            <span class="text-xs text-green-600 block">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                File tersedia ({{ $katalog->file_size }})
                                            </span>
                                        @else
                                            <span class="text-xs text-red-600 block">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                File tidak ditemukan
                                            </span>
                                        @endif
                                    </div>
                                    @if($katalog->pdf_url)
                                        <a href="{{ $katalog->pdf_url }}" target="_blank"
                                           class="ml-3 text-blue-600 hover:text-blue-500">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Upload New File -->
                        <div>
                            <label for="path_pdf" class="block text-sm font-medium text-gray-700 mb-1">
                                @if($katalog && $katalog->path_pdf)
                                    Ganti File PDF <span class="text-gray-500 text-xs">(Opsional)</span>
                                @else
                                    Unggah File PDF <span class="text-red-500">*</span>
                                @endif
                            </label>

                            <div
                                class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
                                id="file-drop-zone">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 48 48"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"/>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="path_pdf"
                                               class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                            <span>Unggah file</span>
                                            <input id="path_pdf"
                                                   name="path_pdf"
                                                   type="file"
                                                   class="sr-only"
                                                   accept=".pdf">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF (maks. 10MB)</p>
                                </div>
                            </div>
                            @error('path_pdf')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script drag & drop -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('File upload script loaded');

            // Get elements
            const form = document.getElementById('katalogForm');
            const fileInput = document.getElementById('path_pdf');
            const fileDropZone = document.getElementById('file-drop-zone');
            const fileNameDisplay = document.getElementById('file-name');
            const fileSizeDisplay = document.getElementById('file-size');
            const hasExistingFile = @json($katalog && $katalog->path_pdf);

            // Prevent default drag behaviors
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Highlight drop zone
            function highlight() {
                fileDropZone.classList.add('border-blue-500', 'bg-blue-50');
            }

            function unhighlight() {
                fileDropZone.classList.remove('border-blue-500', 'bg-blue-50');
            }

            // Event listeners for drag and drop
            ['dragenter', 'dragover'].forEach(eventName => {
                fileDropZone.addEventListener(eventName, highlight, false);
                fileDropZone.addEventListener(eventName, preventDefaults, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                fileDropZone.addEventListener(eventName, unhighlight, false);
                fileDropZone.addEventListener(eventName, preventDefaults, false);
            });

            // Handle dropped files
            fileDropZone.addEventListener('drop', function (e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length) {
                    fileInput.files = files;
                    updateFileInfo(files[0]);
                }
            });

            // Handle file selection via input
            fileInput.addEventListener('change', function () {
                if (this.files.length) {
                    updateFileInfo(this.files[0]);
                }
            });

            // Update UI with file info
            function updateFileInfo(file) {
                console.log('File selected:', file.name, file.size, file.type);

                // Update file name display
                if (fileNameDisplay) {
                    fileNameDisplay.textContent = file.name;
                    fileNameDisplay.classList.remove('hidden');
                }

                // Update file size display
                if (fileSizeDisplay) {
                    const fileSizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                    fileSizeDisplay.textContent = `(${fileSizeInMB} MB)`;
                    fileSizeDisplay.classList.remove('hidden');
                }

                // Update drop zone text
                const dropZoneText = fileDropZone.querySelector('p:not(.text-xs)');
                if (dropZoneText) {
                    dropZoneText.textContent = 'File dipilih: ' + file.name;
                }

                // Show success message
                const successMessage = document.createElement('p');
                successMessage.className = 'mt-2 text-sm text-green-600';
                successMessage.textContent = 'File siap diunggah';

                // Remove any existing messages
                const existingMessage = fileDropZone.querySelector('.text-green-600, .text-red-600');
                if (existingMessage) {
                    existingMessage.remove();
                }

                const spaceY1 = fileDropZone.querySelector('.space-y-1');
                if (spaceY1) {
                    spaceY1.appendChild(successMessage);
                }

                // Check file type and size
                if (file.type !== 'application/pdf') {
                    alert('Hanya file PDF yang diizinkan');
                    fileInput.value = '';
                    return;
                }

                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file melebihi 10MB');
                    fileInput.value = '';
                }
            }

            // Form submission
            form.addEventListener('submit', function () {
                console.log('Form submitted');

                // Show loading state
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = 'Mengunggah...';
                }
            });
        });
    </script>
</x-app-layout>
