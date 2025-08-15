<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <!-- Breadcrumb -->
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('admin.virtual-museum') }}" class="text-gray-400 hover:text-gray-500">Virtual Museum</a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <a href="{{ route('admin.virtual-museum.show', $object->virtualMuseum->museum_id) }}" class="ml-1 text-gray-400 hover:text-gray-500">{{ $object->virtualMuseum->nama }}</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <a href="{{ route('admin.virtual-museum-object.show', $object->object_id) }}" class="ml-1 text-gray-400 hover:text-gray-500">{{ $object->nama }}</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-1 text-gray-500">Edit</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Object: {{ $object->nama }}</h1>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">Untuk {{ $object->virtualMuseum->nama }} - {{ $object->situsPeninggalan->nama }}</p>
                </div>
            </div>
        </div>
    </x-slot>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada input:</h3>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.virtual-museum-object.update', $object->object_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Informasi Object</h2>
                <p class="mt-1 text-sm text-gray-600">Edit data object virtual museum.</p>
            </div>

            <div class="px-6 py-6 space-y-6">
                
                <!-- Nama Object -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Object <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama', $object->nama) }}" 
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Arca Buddha Amitabha"
                           required>
                    <p class="mt-1 text-xs text-gray-500">Nama object yang akan ditampilkan dalam Virtual Museum</p>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Object
                    </label>
                    <textarea id="deskripsi" 
                              name="deskripsi" 
                              rows="4"
                              class="block w-full border border-gray-300 rounded-md px-3 py-2 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Deskripsi detail tentang object ini...">{{ old('deskripsi', $object->deskripsi) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Deskripsi yang akan ditampilkan kepada pengunjung museum</p>
                </div>

            </div>
        </div>

        <!-- Current Files Section -->
        @if($object->gambar_real || $object->path_obj || $object->path_patt || $object->path_gambar_marker)
        <div class="mt-6 bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">File Saat Ini</h2>
                <p class="mt-1 text-sm text-gray-600">File yang sudah diupload untuk object ini.</p>
            </div>
            <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                
                @if($object->gambar_real)
                <div class="border border-gray-200 rounded-lg p-3">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Gambar Real Object</h4>
                    <img src="{{ asset('storage/' . $object->gambar_real) }}" 
                         alt="Current Gambar Real"
                         class="w-full h-24 object-cover rounded">
                    <p class="mt-1 text-xs text-gray-500">{{ basename($object->gambar_real) }}</p>
                </div>
                @endif

                @if($object->path_obj)
                <div class="border border-gray-200 rounded-lg p-3">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">File 3D Object</h4>
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="text-sm text-gray-900">{{ basename($object->path_obj) }}</span>
                    </div>
                </div>
                @endif

                @if($object->path_patt)
                <div class="border border-gray-200 rounded-lg p-3">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">AR Pattern</h4>
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m-6 0h8m-8 0a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2"/>
                        </svg>
                        <span class="text-sm text-gray-900">{{ basename($object->path_patt) }}</span>
                    </div>
                </div>
                @endif

                @if($object->path_gambar_marker)
                <div class="border border-gray-200 rounded-lg p-3">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Gambar Marker AR</h4>
                    <img src="{{ asset('storage/' . $object->path_gambar_marker) }}" 
                         alt="Current AR Marker"
                         class="w-full h-24 object-cover rounded">
                    <p class="mt-1 text-xs text-gray-500">{{ basename($object->path_gambar_marker) }}</p>
                </div>
                @endif

            </div>
        </div>
        @endif

        <!-- File Uploads Section -->
        <div class="mt-6 bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Upload File Baru</h2>
                <p class="mt-1 text-sm text-gray-600">Upload file baru jika ingin mengganti file yang sudah ada. Kosongkan jika tidak ingin mengubah.</p>
            </div>

            <div class="px-6 py-6 space-y-6">
                
                <!-- Gambar Real Object -->
                <div>
                    <label for="gambar_real" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Real Object {{ $object->gambar_real ? '(Ganti)' : '' }}
                    </label>
                    <div id="gambar_real_dropzone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors cursor-pointer">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="gambar_real" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span id="gambar_real_text">Upload gambar</span>
                                    <input id="gambar_real" name="gambar_real" type="file" class="sr-only" accept="image/*" onchange="handleFileSelect('gambar_real')">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Foto asli dari object untuk referensi pengunjung</p>
                </div>

                <!-- File 3D Object -->
                <div>
                    <label for="path_obj" class="block text-sm font-medium text-gray-700 mb-2">
                        File 3D Object {{ $object->path_obj ? '(Ganti)' : '' }}
                    </label>
                    <div id="path_obj_dropzone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors cursor-pointer">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="path_obj" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span id="path_obj_text">Upload file 3D</span>
                                    <input id="path_obj" name="path_obj" type="file" class="sr-only" accept=".obj,.glb,.gltf" onchange="handleFileSelect('path_obj')">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">OBJ, GLB, GLTF up to 50MB</p>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Model 3D object untuk ditampilkan dalam Virtual Museum</p>
                </div>

                <!-- AR Pattern File -->
                <div>
                    <label for="path_patt" class="block text-sm font-medium text-gray-700 mb-2">
                        AR Pattern File {{ $object->path_patt ? '(Ganti)' : '' }}
                    </label>
                    <div id="path_patt_dropzone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors cursor-pointer">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="path_patt" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span id="path_patt_text">Upload pattern</span>
                                    <input id="path_patt" name="path_patt" type="file" class="sr-only" accept=".patt" onchange="handleFileSelect('path_patt')">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PATT up to 10MB</p>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">File pattern untuk Augmented Reality (AR)</p>
                </div>

                <!-- AR Marker Image -->
                <div>
                    <label for="path_gambar_marker" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Marker AR {{ $object->path_gambar_marker ? '(Ganti)' : '' }}
                    </label>
                    <div id="path_gambar_marker_dropzone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors cursor-pointer">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="path_gambar_marker" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span id="path_gambar_marker_text">Upload gambar marker</span>
                                    <input id="path_gambar_marker" name="path_gambar_marker" type="file" class="sr-only" accept="image/*" onchange="handleFileSelect('path_gambar_marker')">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Gambar marker yang akan digunakan untuk mendeteksi AR</p>
                </div>

            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4 sm:justify-end">
            <a href="{{ route('admin.virtual-museum-object.show', $object->object_id) }}" 
               class="w-full sm:w-auto order-2 sm:order-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </a>
            <button type="submit" 
                    class="w-full sm:w-auto order-1 sm:order-2 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    // File upload handling
    function handleFileSelect(inputId) {
        const input = document.getElementById(inputId);
        const textElement = document.getElementById(inputId + '_text');
        const dropzoneElement = document.getElementById(inputId + '_dropzone');
        
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB
            
            textElement.textContent = `${fileName} (${fileSize} MB)`;
            dropzoneElement.classList.remove('border-gray-300');
            dropzoneElement.classList.add('border-green-400', 'bg-green-50');
        } else {
            // Reset to default state
            resetFileInput(inputId);
        }
    }
    
    function resetFileInput(inputId) {
        const textElement = document.getElementById(inputId + '_text');
        const dropzoneElement = document.getElementById(inputId + '_dropzone');
        
        dropzoneElement.classList.remove('border-green-400', 'bg-green-50');
        dropzoneElement.classList.add('border-gray-300');
        
        switch(inputId) {
            case 'gambar_real':
                textElement.textContent = 'Upload gambar';
                break;
            case 'path_obj':
                textElement.textContent = 'Upload file 3D';
                break;
            case 'path_patt':
                textElement.textContent = 'Upload pattern';
                break;
            case 'path_gambar_marker':
                textElement.textContent = 'Upload gambar marker';
                break;
        }
    }
    
    // Drag and drop functionality
    function setupDragAndDrop(inputId) {
        const dropzoneElement = document.getElementById(inputId + '_dropzone');
        const inputElement = document.getElementById(inputId);
        
        dropzoneElement.addEventListener('click', function() {
            inputElement.click();
        });
        
        dropzoneElement.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropzoneElement.classList.add('border-blue-400', 'bg-blue-50');
        });
        
        dropzoneElement.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropzoneElement.classList.remove('border-blue-400', 'bg-blue-50');
        });
        
        dropzoneElement.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropzoneElement.classList.remove('border-blue-400', 'bg-blue-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                inputElement.files = files;
                handleFileSelect(inputId);
            }
        });
    }
    
    // Initialize drag and drop for all file inputs
    document.addEventListener('DOMContentLoaded', function() {
        setupDragAndDrop('gambar_real');
        setupDragAndDrop('path_obj');
        setupDragAndDrop('path_patt');
        setupDragAndDrop('path_gambar_marker');
    });
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const namaInput = document.getElementById('nama');
        
        if (!namaInput.value.trim()) {
            e.preventDefault();
            alert('Nama object harus diisi!');
            namaInput.focus();
            return false;
        }
        
        // Show loading state
        const submitButton = document.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;
        
        // Reset button after 30 seconds (failsafe)
        setTimeout(function() {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }, 30000);
    });
</script>
</x-app-layout>
