<x-app-layout>
    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Kelola Materi</h1>
                        <p class="mt-2 text-sm text-gray-600 sm:text-base">Kelola semua materi pembelajaran dalam sistem
                        </p>
                    </div>
                    <div class="flex flex-col space-y-2 sm:flex-row sm:space-x-3 sm:space-y-0">
                        <a href="{{ route('admin.materi.create') }}"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Materi
                        </a>
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span class="hidden sm:inline">Kembali ke Dashboard</span>
                            <span class="sm:hidden">Dashboard</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="relative mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="relative mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Materi Content -->
            <div class="overflow-hidden bg-white shadow sm:rounded-md">
                <div class="px-4 py-5 sm:p-6">
                    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <p class="mb-2 text-sm text-gray-600 sm:mb-0">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span class="hidden sm:inline">Drag & drop baris tabel untuk mengubah urutan materi</span>
                            <span class="sm:hidden">Geser untuk ubah urutan</span>
                        </p>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs text-gray-500">Total: {{ $materis->total() }} materi</span>
                        </div>
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden overflow-x-auto lg:block">
                        <table class="min-w-full divide-y divide-gray-200" id="materis-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        <i class="fas fa-arrows-alt text-gray-400"></i>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Urutan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Era
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Bab
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Judul Materi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Deskripsi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Pretest
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Posttest
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Dibuat
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white" id="sortable-tbody">
                                @forelse($materis as $materi)
                                    <tr class="sortable-row cursor-move hover:bg-gray-50"
                                        data-id="{{ $materi->materi_id }}">
                                        <td class="whitespace-nowrap px-2 py-4 text-center">
                                            <i class="fas fa-grip-vertical drag-handle text-gray-400"></i>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                #{{ $materi->urutan }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                            @if ($materi->era)
                                                {{ $materi->era->kode }}. {{ $materi->era->nama }}
                                            @else
                                                <span class="text-gray-400">Belum dipilih</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                            {{ $materi->bab ? 'Bab ' . $materi->bab : '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $materi->judul }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ Str::limit($materi->deskripsi, 60) }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                class="{{ $materi->pretest()->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                                {{ $materi->pretest()->count() }} Soal
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                class="{{ $materi->posttest()->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                                {{ $materi->posttest()->count() }} Soal
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ $materi->created_at->format('d M Y') }}
                                        </td>
                                        <td class="space-x-2 whitespace-nowrap px-6 py-4 text-sm font-medium">
                                            <a href="{{ route('admin.materi.show', $materi->materi_id) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.materi.edit', $materi->materi_id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.materi.destroy', $materi->materi_id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-12 text-center">
                                            <div class="text-gray-500">
                                                <i class="fas fa-book mb-4 text-4xl"></i>
                                                <p class="text-lg font-medium">Belum Ada Materi</p>
                                                <p class="text-sm">Mulai dengan menambahkan materi pembelajaran pertama.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="lg:hidden">
                        <div id="sortable-cards" class="space-y-4">
                            @forelse($materis as $materi)
                                <div class="sortable-card cursor-move rounded-lg border border-gray-200 bg-white p-4"
                                    data-id="{{ $materi->materi_id }}">
                                    <div class="mb-3 flex items-start justify-between">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-grip-vertical drag-handle text-gray-400"></i>
                                            <span
                                                class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                #{{ $materi->urutan }}
                                            </span>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.materi.show', $materi->materi_id) }}"
                                                class="p-1 text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.materi.edit', $materi->materi_id) }}"
                                                class="p-1 text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.materi.destroy', $materi->materi_id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <h3 class="mb-2 text-lg font-medium text-gray-900">{{ $materi->judul }}</h3>
                                    <p class="mb-2 text-xs text-gray-500">
                                        @if ($materi->era)
                                            {{ $materi->era->kode }}. {{ $materi->era->nama }}
                                        @else
                                            Era belum dipilih
                                        @endif
                                        @if ($materi->bab)
                                            • Bab {{ $materi->bab }}
                                        @endif
                                    </p>
                                    <p class="mb-3 text-sm text-gray-600">{{ Str::limit($materi->deskripsi, 100) }}
                                    </p>

                                    <div class="mb-3 grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="mb-1 block text-xs text-gray-500">Pretest</span>
                                            <span
                                                class="{{ $materi->pretest()->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                                {{ $materi->pretest()->count() }} Soal
                                            </span>
                                        </div>
                                        <div>
                                            <span class="mb-1 block text-xs text-gray-500">Posttest</span>
                                            <span
                                                class="{{ $materi->posttest()->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                                {{ $materi->posttest()->count() }} Soal
                                            </span>
                                        </div>
                                    </div>

                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Dibuat {{ $materi->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            @empty
                                <div class="py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-book mb-4 text-4xl"></i>
                                        <p class="text-lg font-medium">Belum Ada Materi</p>
                                        <p class="text-sm">Mulai dengan menambahkan materi pembelajaran pertama.</p>
                                        <a href="{{ route('admin.materi.create') }}"
                                            class="mt-4 inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                                            <i class="fas fa-plus mr-2"></i>
                                            Tambah Materi
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if ($materis->hasPages())
                        <div class="mt-6 flex justify-center">
                            {{ $materis->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SortableJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Desktop table sortable
            const tbody = document.getElementById('sortable-tbody');
            const cardsContainer = document.getElementById('sortable-cards');

            if (tbody && tbody.children.length > 1) {
                const sortableTable = Sortable.create(tbody, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onEnd: function(evt) {
                        updateMateriOrder();
                    }
                });
            }

            // Mobile cards sortable
            if (cardsContainer && cardsContainer.children.length > 1) {
                const sortableCards = Sortable.create(cardsContainer, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onEnd: function(evt) {
                        updateMateriOrderCards();
                    }
                });
            }

            function updateMateriOrder() {
                const rows = document.querySelectorAll('.sortable-row');
                const items = [];

                rows.forEach((row, index) => {
                    const id = row.dataset.id;
                    const position = index + 1;
                    items.push({
                        id: parseInt(id),
                        position: position
                    });

                    // Update visual order number
                    const orderBadge = row.querySelector('.bg-blue-100');
                    if (orderBadge) {
                        orderBadge.textContent = '#' + position;
                    }
                });

                sendOrderUpdate(items);
            }

            function updateMateriOrderCards() {
                const cards = document.querySelectorAll('.sortable-card');
                const items = [];

                cards.forEach((card, index) => {
                    const id = card.dataset.id;
                    const position = index + 1;
                    items.push({
                        id: parseInt(id),
                        position: position
                    });

                    // Update visual order number
                    const orderBadge = card.querySelector('.bg-blue-100');
                    if (orderBadge) {
                        orderBadge.textContent = '#' + position;
                    }
                });

                sendOrderUpdate(items);
            }

            function sendOrderUpdate(items) {
                // Send AJAX request to update order
                fetch('{{ route('admin.materi.update-order') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            items: items
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message briefly
                            showMessage('Urutan materi berhasil diperbarui!', 'success');
                        } else {
                            showMessage('Gagal memperbarui urutan materi.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('Terjadi kesalahan saat memperbarui urutan.', 'error');
                    });
            }

            function showMessage(message, type) {
                const messageDiv = document.createElement('div');
                messageDiv.className =
                    `fixed top-4 right-4 z-50 px-4 py-2 rounded-md text-sm font-medium ${type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'}`;
                messageDiv.textContent = message;

                document.body.appendChild(messageDiv);

                setTimeout(() => {
                    messageDiv.remove();
                }, 3000);
            }
        });

        // Add CSS for sortable states
        const style = document.createElement('style');
        style.textContent = `
            .sortable-ghost {
                opacity: 0.4;
            }
            .sortable-chosen {
                background-color: #f3f4f6;
            }
            .sortable-drag {
                opacity: 0.8;
                transform: rotate(2deg);
            }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>
