<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Kritik & Saran</h1>
                        <p class="mt-2 text-sm sm:text-base text-gray-600">Kelola dan review kritik serta saran dari pengguna</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span class="hidden sm:inline">Kembali ke Dashboard</span>
                            <span class="sm:hidden">Dashboard</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-comments text-xl sm:text-2xl text-blue-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Feedback</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $feedback->total() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-xl sm:text-2xl text-orange-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Hari Ini</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $feedback->where('created_at', '>=', now()->startOfDay())->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-week text-xl sm:text-2xl text-green-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Minggu Ini</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $feedback->where('created_at', '>=', now()->startOfWeek())->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-xl sm:text-2xl text-purple-600"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Pengguna Aktif</dt>
                                    <dd class="text-lg sm:text-xl font-semibold text-gray-900">{{ $feedback->pluck('user_id')->unique()->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            @if($feedback->count() > 0)
                <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Kritik & Saran</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Review dan kelola feedback dari pengguna aplikasi
                                </p>
                            </div>
                            <div class="mt-3 sm:mt-0 text-sm text-gray-500">
                                Total: {{ $feedback->total() }} feedback
                            </div>
                        </div>
                    </div>

                    <!-- Desktop View -->
                    <div class="hidden md:block">
                        <div class="divide-y divide-gray-200">
                            @foreach($feedback as $item)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-4 flex-1">
                                        <!-- User Avatar -->
                                        <div class="flex-shrink-0">
                                            @if($item->user && $item->user->profile_photo)
                                                <img class="h-12 w-12 rounded-full border-2 border-gray-200" src="{{ asset('storage/' . $item->user->profile_photo) }}" alt="Profile">
                                            @else
                                                <div class="h-12 w-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center border-2 border-gray-200">
                                                    <i class="fas fa-user text-white text-lg"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <!-- Header -->
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center space-x-3">
                                                    <h4 class="text-sm font-semibold text-gray-900">
                                                        {{ $item->user->name ?? 'Pengguna Tidak Ditemukan' }}
                                                    </h4>
                                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                        Feedback #{{ $item->ks_id }}
                                                    </span>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-sm text-gray-900">{{ $item->created_at->format('d/m/Y') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }}</div>
                                                    <div class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            @if($item->user)
                                            <div class="mb-3">
                                                <span class="inline-flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                                    {{ $item->user->email }}
                                                </span>
                                            </div>
                                            @endif

                                            <!-- Feedback Content -->
                                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                                <div class="flex items-start">
                                                    <i class="fas fa-quote-left text-gray-400 text-sm mt-1 mr-3"></i>
                                                    <p class="text-gray-800 text-sm leading-relaxed whitespace-pre-line">{{ $item->pesan }}</p>
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex space-x-3">
                                                    @if($item->user)
                                                    <a href="mailto:{{ $item->user->email }}?subject=Re: Feedback Anda" class="inline-flex items-center px-3 py-1.5 border border-green-300 shadow-sm text-xs font-medium rounded text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                                        <i class="fas fa-reply mr-1.5"></i>
                                                        Balas via Email
                                                    </a>
                                                    @endif
                                                    <button onclick="toggleDetails({{ $item->ks_id }})" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                        <i class="fas fa-info-circle mr-1.5"></i>
                                                        Detail
                                                    </button>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <form action="{{ route('admin.feedback.destroy', $item->ks_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus feedback ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                                            <i class="fas fa-trash mr-1.5"></i>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Details Panel (Hidden by default) -->
                                            <div id="details-{{ $item->ks_id }}" class="hidden mt-4 pt-4 border-t border-gray-200">
                                                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                                    <div>
                                                        <dt class="font-medium text-gray-500">ID Feedback</dt>
                                                        <dd class="mt-1 font-mono text-gray-900">#{{ $item->ks_id }}</dd>
                                                    </div>
                                                    <div>
                                                        <dt class="font-medium text-gray-500">Waktu Submit</dt>
                                                        <dd class="mt-1 text-gray-900">{{ $item->created_at->format('d F Y, H:i:s') }}</dd>
                                                    </div>
                                                    <div>
                                                        <dt class="font-medium text-gray-500">Panjang Pesan</dt>
                                                        <dd class="mt-1 text-gray-900">{{ strlen($item->pesan) }} karakter</dd>
                                                    </div>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Mobile View -->
                    <div class="md:hidden">
                        <div class="divide-y divide-gray-200">
                            @foreach($feedback as $item)
                            <div class="p-4">
                                <div class="flex items-start space-x-3">
                                    <!-- User Avatar -->
                                    <div class="flex-shrink-0">
                                        @if($item->user && $item->user->profile_photo)
                                            <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $item->user->profile_photo) }}" alt="Profile">
                                        @else
                                            <div class="h-10 w-10 bg-blue-600 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-white text-sm"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Header -->
                                        <div class="mb-2">
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $item->user->name ?? 'User Tidak Ditemukan' }}
                                                </h4>
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                    #{{ $item->ks_id }}
                                                </span>
                                            </div>
                                            @if($item->user)
                                            <p class="text-xs text-gray-500 mt-1">{{ $item->user->email }}</p>
                                            @endif
                                        </div>

                                        <!-- Feedback Content -->
                                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                            <p class="text-sm text-gray-800 leading-relaxed">{{ Str::limit($item->pesan, 120) }}</p>
                                            @if(strlen($item->pesan) > 120)
                                                <button onclick="showFullMessage({{ $item->ks_id }})" class="text-xs text-blue-600 hover:text-blue-800 mt-2">
                                                    Lihat selengkapnya...
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Date -->
                                        <div class="mb-3">
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                {{ $item->created_at->format('d/m/Y H:i') }}
                                            </span>
                                            <span class="text-xs text-gray-500 ml-2">{{ $item->created_at->diffForHumans() }}</span>
                                        </div>

                                        <!-- Mobile Actions -->
                                        <div class="flex space-x-2">
                                            @if($item->user)
                                            <a href="mailto:{{ $item->user->email }}?subject=Re: Feedback Anda"
                                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-green-300 shadow-sm text-xs font-medium rounded text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                                <i class="fas fa-reply mr-1"></i>
                                                Balas
                                            </a>
                                            @endif
                                            <form action="{{ route('admin.feedback.destroy', $item->ks_id) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus feedback ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($feedback->hasPages())
                        <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                            {{ $feedback->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white shadow-lg sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-12 text-center">
                        <div class="mx-auto h-20 w-20 sm:h-24 sm:w-24 text-gray-400">
                            <i class="fas fa-comments text-5xl sm:text-6xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Kritik & Saran</h3>
                        <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto px-4">
                            Belum ada feedback dari pengguna. Kritik dan saran akan muncul di sini ketika pengguna mengirimkan feedback.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Full Message Modal -->
    <div id="messageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 p-4" style="display: none;">
        <div class="flex items-center justify-center w-full h-full">
            <div class="bg-white rounded-lg max-w-2xl max-h-full overflow-auto p-6 relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Pesan Lengkap</h3>
                    <button onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="modalContent" class="prose prose-sm max-w-none">
                    <!-- Content will be filled by JavaScript -->
                </div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closeMessageModal()" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDetails(feedbackId) {
            const element = document.getElementById('details-' + feedbackId);
            element.classList.toggle('hidden');
        }

        function showFullMessage(feedbackId) {
            // This would need to be implemented with AJAX or by passing full message data
            // For now, just show the modal
            const modal = document.getElementById('messageModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeMessageModal() {
            const modal = document.getElementById('messageModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMessageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMessageModal();
            }
        });
    </script>
</x-app-layout>
