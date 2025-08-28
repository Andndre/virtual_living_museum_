<x-guest-layout>
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('guest.statistik') }}" class="back-button p-2 hover:bg-white/10 rounded-full transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-bold">Rapor Pembelajaran</h1>
            <div class="w-8"></div> <!-- For alignment -->
        </div>
    </div>

    <div class="px-6 py-6 bg-white min-h-screen pb-32">
        <!-- Overall Score Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-8 shadow-sm border border-gray-100">
            <div class="text-center mb-4">
                <p class="text-gray-600 text-sm mb-1">Total Nilai Posttest</p>
                <div class="text-4xl font-bold text-gray-800">{{ $totalScore }}<span class="text-lg text-gray-500">/{{ $maxTotalScore }}</span></div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $overallPercentage }}%"></div>
                </div>
                <p class="text-sm text-gray-500 mt-2">Rata-rata: {{ round($overallPercentage) }}%</p>
            </div>
        </div>

        <!-- Materials List -->
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Nilai Per Materi</h2>

        @forelse($materials as $material)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-medium text-gray-800">{{ $material['judul'] }}</h3>
                </div>

                <!-- Pretest Score (Reference Only) -->
                <div class="p-4 border-b border-gray-50 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Nilai Awal (Pretest)</span>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $material['pretest']['correct'] ?? 0 }} dari {{ $material['pretest']['total'] ?? 0 }} soal
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-bold {{ $material['pretest']['score'] >= 75 ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $material['pretest']['score'] ?? 0 }}
                            </span>
                            <span class="text-xs text-gray-400">/100</span>
                        </div>
                    </div>
                    @if($material['pretest']['total'] > 0)
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                        <div class="bg-gray-400 h-1.5 rounded-full" style="width: {{ $material['pretest']['score'] ?? 0 }}%"></div>
                    </div>
                    @endif
                </div>

                <!-- Posttest Score -->
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Nilai Akhir (Posttest)</span>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $material['posttest']['correct'] ?? 0 }} dari {{ $material['posttest']['total'] ?? 0 }} soal
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold {{ $material['posttest']['score'] >= 75 ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $material['posttest']['score'] ?? 0 }}
                            </span>
                            <span class="text-xs text-gray-400">/100</span>
                        </div>
                    </div>
                    @if($material['posttest']['total'] > 0)
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $material['posttest']['score'] ?? 0 }}%"></div>
                    </div>
                    @endif
                </div>

                <!-- Progress Indicator -->
                @if(isset($material['pretest']['score']) && $material['pretest']['score'] > 0)
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                    <div class="flex justify-between items-center text-xs text-gray-500 mb-1">
                        <span>Perkembangan</span>
                        @php
                            $progress = $material['posttest']['score'] - $material['pretest']['score'];
                            $progressPercentage = $material['pretest']['score'] > 0 
                                ? (($material['posttest']['score'] - $material['pretest']['score']) / $material['pretest']['score']) * 100 
                                : 100;
                        @endphp
                        <span class="font-medium {{ $progress >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $progress >= 0 ? '+' : '' }}{{ $progress }} poin
                            ({{ number_format(abs($progressPercentage), 0) }}% {{ $progress >= 0 ? 'naik' : 'turun' }})
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $progress >= 0 ? 'bg-green-500' : 'bg-red-400' }}" 
                             style="width: {{ min(100, abs($progressPercentage)) }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        @empty
            <div class="text-center py-10">
                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-clipboard text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500">Belum ada data rapor yang tersedia</p>
            </div>
        @endforelse
    </div>

    <!-- Bottom Navigation -->
    <x-bottom-nav />
</x-guest-layout>
