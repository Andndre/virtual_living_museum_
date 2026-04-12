<x-app-layout>
    <x-slot name="header">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <!-- Breadcrumb -->
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('admin.virtual-museum') }}" class="text-gray-400 hover:text-gray-500">Virtual Living Museum</a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-1 text-gray-500">Tambah Baru</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Virtual Living Museum</h1>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">Buat Virtual Living Museum baru untuk situs peninggalan</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('admin.virtual-museum') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    </x-slot>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada inputan:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.virtual-museum.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Main Information Card -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Virtual Living Museum</h3>
                <p class="mt-1 text-sm text-gray-500">Masukkan detail dasar untuk Virtual Living Museum</p>
            </div>
            <div class="px-6 py-6 space-y-6">

                <!-- Situs Peninggalan Selection -->
                <div>
                    <label for="situs_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Situs Peninggalan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="situs_id" name="situs_id" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-md" required>
                            <option value="">Pilih Situs Peninggalan</option>
                            @foreach($situsOptions as $situs)
                                <option value="{{ $situs->situs_id }}"
                                    {{ (old('situs_id') == $situs->situs_id || (isset($selectedSitusId) && $selectedSitusId == $situs->situs_id)) ? 'selected' : '' }}>
                                    {{ $situs->nama }} - {{ $situs->alamat }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Pilih situs peninggalan yang akan memiliki Virtual Living Museum ini. Satu situs dapat memiliki beberapa Virtual Living Museum.</p>
                </div>

                <!-- Nama Virtual Living Museum -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Virtual Living Museum <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nama"
                           name="nama"
                           value="{{ old('nama') }}"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Museum Virtual Candi Borobudur"
                           required>
                    <p class="mt-1 text-xs text-gray-500">Nama yang akan ditampilkan untuk Virtual Living Museum</p>
                </div>

                <!-- File GLB Upload -->
                <div>
                    <label for="obj_file" class="block text-sm font-medium text-gray-700 mb-2">
                        File Model 3D Museum (GLB) <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors" id="drop-area">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="obj_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload file</span>
                                    <input id="obj_file" name="obj_file" type="file" class="sr-only" accept=".glb" onchange="handleGlbFileSelect(this)">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">GLB up to 300MB</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div id="upload-progress-container" class="hidden mt-3">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span id="upload-filename">-</span>
                            <span id="upload-percent">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="upload-progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p id="upload-status" class="text-xs text-gray-500 mt-1">Mengunggah...</p>
                    </div>

                    <!-- Uploaded file info -->
                    <div id="file-name" class="mt-2 text-sm text-gray-600 hidden"></div>
                    <p class="mt-1 text-xs text-gray-500">Upload file model 3D museum dalam format GLB (GL Transmission Format Binary)</p>
                </div>

            </div>
        </div>

        <!-- Information Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Virtual Living Museum merupakan representasi 3D dari keseluruhan museum untuk situs peninggalan</li>
                            <li>Setiap situs peninggalan hanya dapat memiliki satu Virtual Living Museum</li>
                            <li>Virtual Living Museum dapat memiliki banyak Virtual Living Museum Object di dalamnya</li>
                            <li>File GLB harus berukuran maksimal 300MB untuk performa optimal</li>
                            <li>Format GLB mendukung tekstur, animasi, dan material yang kompleks</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
            <a href="{{ route('admin.virtual-museum') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Simpan Virtual Living Museum
            </button>
        </div>

    </form>
</div>

<script>
// Chunk size: 2MB per chunk — safe for any connection
const CHUNK_SIZE = 2 * 1024 * 1024;

let _uploadUuid = null;
let _uploadFieldName = null;
let _uploadTargetPath = null;
let _uploadOriginalFilename = null;
let _uploadMuseumId = null;

function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

function handleGlbFileSelect(input) {
    const file = input.files[0];
    if (!file) return;

    if (!file.name.toLowerCase().endsWith('.glb')) {
        alert('Hanya file GLB yang diizinkan!');
        input.value = '';
        return;
    }
    if (file.size > 300 * 1024 * 1024) {
        alert('File terlalu besar! Maksimal 300MB.');
        input.value = '';
        return;
    }

    _uploadUuid = generateUUID();
    _uploadFieldName = 'obj_file';
    _uploadTargetPath = 'virtual-museum/models';
    _uploadOriginalFilename = file.name;
    _uploadMuseumId = null;

    uploadChunks(file);
}

async function uploadChunks(file) {
    const totalChunks = Math.ceil(file.size / CHUNK_SIZE);

    showProgress(file.name, 0);

    try {
        for (let i = 0; i < totalChunks; i++) {
            const start = i * CHUNK_SIZE;
            const end = Math.min(start + CHUNK_SIZE, file.size);
            const chunk = file.slice(start, end);

            await uploadChunk(chunk, i, totalChunks, file.name);

            const pct = Math.round(((i + 1) / totalChunks) * 100);
            updateProgress(pct, `Mengunggah chunk ${i + 1}/${totalChunks}`);
        }

        updateProgress(100, 'Menyatukan file...');
        const result = await finalizeUpload(file.name);

        if (result.success) {
            updateProgress(100, 'Selesai!');
            // Add a hidden input so the form knows the final path
            let hidden = document.getElementById('_uploaded_path_obj');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.id = '_uploaded_path_obj';
                hidden.name = '_uploaded_path_obj';
                document.querySelector('form').appendChild(hidden);
            }
            hidden.value = result.path;

            showUploadedFile(file.name, file.size, result.path);
        } else {
            showError(result.error || 'Upload gagal.');
            document.getElementById('obj_file').value = '';
        }
    } catch (err) {
        showError('Upload gagal: ' + err.message);
        document.getElementById('obj_file').value = '';
    }
}

async function uploadChunk(chunk, chunkIndex, totalChunks, fileName) {
    const formData = new FormData();
    formData.append('file', chunk, fileName);
    formData.append('chunkIndex', chunkIndex);
    formData.append('totalChunks', totalChunks);
    formData.append('uuid', _uploadUuid);
    formData.append('fieldName', _uploadFieldName);
    if (_uploadMuseumId) formData.append('museum_id', _uploadMuseumId);

    const response = await fetch('/admin/chunk-upload/chunk', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData,
    });

    if (!response.ok) {
        const err = await response.json().catch(() => ({}));
        throw new Error(err.error || `HTTP ${response.status}`);
    }
}

async function finalizeUpload(fileName) {
    const formData = new FormData();
    formData.append('uuid', _uploadUuid);
    formData.append('fieldName', _uploadFieldName);
    formData.append('target_path', _uploadTargetPath);
    formData.append('original_filename', fileName);
    if (_uploadMuseumId) formData.append('museum_id', _uploadMuseumId);

    const response = await fetch('/admin/chunk-upload/finalize', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData,
    });

    return response.json();
}

function showProgress(fileName, pct) {
    const container = document.getElementById('upload-progress-container');
    document.getElementById('upload-filename').textContent = fileName;
    document.getElementById('upload-percent').textContent = pct + '%';
    document.getElementById('upload-progress-bar').style.width = pct + '%';
    document.getElementById('upload-status').textContent = 'Mengunggah...';
    container.classList.remove('hidden');
}

function updateProgress(pct, status) {
    document.getElementById('upload-percent').textContent = pct + '%';
    document.getElementById('upload-progress-bar').style.width = pct + '%';
    document.getElementById('upload-status').textContent = status;
}

function showUploadedFile(fileName, fileSize, path) {
    document.getElementById('upload-progress-container').classList.add('hidden');
    const fileSizeMB = (fileSize / 1024 / 1024).toFixed(2);
    const fileNameDiv = document.getElementById('file-name');
    fileNameDiv.innerHTML = `
        <div class="flex items-center space-x-2 p-3 bg-green-50 border border-green-200 rounded-lg">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">${fileName}</p>
                <p class="text-xs text-gray-500">${fileSizeMB} MB — berhasil diunggah</p>
            </div>
            <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;
    fileNameDiv.classList.remove('hidden');
}

function showError(msg) {
    const container = document.getElementById('upload-progress-container');
    document.getElementById('upload-status').textContent = msg;
    document.getElementById('upload-status').classList.add('text-red-600');
}

function clearFile() {
    document.getElementById('obj_file').value = '';
    document.getElementById('file-name').classList.add('hidden');
    document.getElementById('upload-progress-container').classList.add('hidden');
    const hidden = document.getElementById('_uploaded_path_obj');
    if (hidden) hidden.remove();
    _uploadUuid = null;
}

// Drag and Drop functionality
const dropArea = document.getElementById('drop-area');
const fileInput = document.getElementById('obj_file');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
});

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

dropArea.addEventListener('drop', handleDrop, false);

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight(e) {
    dropArea.classList.add('border-blue-500', 'bg-blue-50');
}

function unhighlight(e) {
    dropArea.classList.remove('border-blue-500', 'bg-blue-50');
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;

    if (files.length > 0) {
        const file = files[0];
        if (file.name.toLowerCase().endsWith('.glb')) {
            if (file.size <= 300 * 1024 * 1024) {
                fileInput.files = files;
                handleGlbFileSelect(fileInput);
            } else {
                alert('File terlalu besar! Maksimal 300MB.');
            }
        } else {
            alert('Hanya file GLB yang diizinkan!');
        }
    }
}

// Form submit: pass uploaded path as obj_file value
document.querySelector('form').addEventListener('submit', function (e) {
    const uploadedPath = document.getElementById('_uploaded_path_obj');
    if (uploadedPath && uploadedPath.value) {
        // Replace the real file input with a hidden field holding the already-uploaded path
        const dummyFileInput = document.getElementById('obj_file');
        dummyFileInput.removeAttribute('required');

        const pathField = document.createElement('input');
        pathField.type = 'hidden';
        pathField.name = 'obj_file_path'; // server reads this
        pathField.value = uploadedPath.value;
        this.appendChild(pathField);

        // Disable real file input so it's not sent
        dummyFileInput.disabled = true;
    }
});
</script>
</x-app-layout>
