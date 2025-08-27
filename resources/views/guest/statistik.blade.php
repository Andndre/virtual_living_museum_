<x-guest-layout>
    {{-- Header Section --}}
    <div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center mb-6">
            <button type="button" class="back-button p-2 hover:bg-white/10 rounded-full transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </button>
            <a href="{{ route('profile.edit') }}" class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/30 hover:border-white/50 transition-colors">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Picture" class="w-full h-full object-cover" />
                @else
                    <img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="Profile Picture" class="w-full h-full object-cover" />
                @endif
            </a>
        </div>

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Statistik</h1>
        </div>

        <!-- User's ranking showcase -->
        <div class="flex justify-center mb-8">
            <div class="relative">
                <!-- User Photo -->
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white/50 mx-auto">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Picture" class="w-full h-full object-cover" />
                    @else
                        <img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="Profile Picture" class="w-full h-full object-cover" />
                    @endif
                </div>

                <!-- Rank -->
                <div class="text-center mt-4">
                    <h2 class="text-4xl font-bold">{{ $currentUserRank > 0 ? $currentUserRank : '-' }}{{ $currentUserRank === 1 ? 'st' : ($currentUserRank === 2 ? 'nd' : ($currentUserRank === 3 ? 'rd' : 'th')) }}</h2>
                    <p class="text-sm opacity-80">{{ $userScore }}pts</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="px-6 py-8 bg-white rounded-t-3xl -mt-6 min-h-screen">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Leaderboard</h2>
        </div>

        <!-- Leaderboard -->
        <div class="space-y-4">
            @foreach($topUsers as $user)
                <div class="flex items-center p-3 rounded-lg {{ $user->id === $currentUser->id ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50' }}">
                    <!-- Rank -->
                    <div class="w-8 text-center font-bold {{ $user->rank <= 3 ? 'text-blue-600' : 'text-gray-700' }}">
                        {{ $user->rank }}
                    </div>

                    <!-- User photo -->
                    <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200 ml-2">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover" />
                        @else
                            <img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="{{ $user->name }}" class="w-full h-full object-cover" />
                        @endif
                    </div>

                    <!-- User name -->
                    <div class="flex-1 ml-3">
                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                    </div>

                    <!-- Score -->
                    <div class="text-right font-bold text-gray-900">
                        {{ $user->total_score }}pts
                    </div>
                </div>
            @endforeach

            @if(count($topUsers) === 0)
                <div class="text-center py-10">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-trophy text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500">Belum ada data leaderboard</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Bottom Navigation --}}
    <x-bottom-nav />
</x-guest-layout>
