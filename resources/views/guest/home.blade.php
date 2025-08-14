<x-guest-layout>
	{{-- Header Section --}}
	<div class="px-6 py-6 bg-primary text-white">
		<div class="flex justify-between items-center">
			<x-application-logo class="w-12 h-12" />
			<a href="{{ route('profile.edit') }}" class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/30 hover:border-white/50 transition-colors">
				@if(auth()->user()->profile_photo)
					<img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Picture" class="w-full h-full object-cover" />
				@else
					<img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="Profile Picture" class="w-full h-full object-cover" />
				@endif
			</a>
		</div>

		<div class="pt-8 pb-8">
			<p class="text-sm opacity-90">{{ $greeting }},</p>
			<h1 class="text-xl font-bold">{{ auth()->user()->name }}</h1>
		</div>
	</div>

	{{-- Menu Grid Section --}}
	<div class="px-6 py-8 pb-24 bg-gray-50 rounded-t-3xl -mt-6 relative">
		<div class="grid grid-cols-3 gap-6">
			{{-- E-Learning --}}
			<div class="flex flex-col items-center">
				<a href="#" class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
					<img src="{{ asset('images/icons/elearning.png') }}" alt="E-Learning" class="w-12 h-12" />
				</a>
				<span class="text-xs font-medium text-gray-700 text-center">{{ __('app.elearning') }}</span>
			</div>

			{{-- Maps --}}
			<div class="flex flex-col items-center">
				<a href="#" class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
					<img src="{{ asset('images/icons/maps.png') }}" alt="Maps" class="w-12 h-12" />
				</a>
				<span class="text-xs font-medium text-gray-700 text-center">{{ __('app.maps') }}</span>
			</div>

			{{-- Statistik --}}
			<div class="flex flex-col items-center">
				<a href="#" class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
					<img src="{{ asset('images/icons/statistik.png') }}" alt="Statistik" class="w-12 h-12" />
				</a>
				<span class="text-xs font-medium text-gray-700 text-center">{{ __('app.statistics') }}</span>
			</div>

			{{-- Lapor Peninggalan --}}
			<div class="flex flex-col items-center">
				<a href="#" class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
					<img src="{{ asset('images/icons/lapor-peninggalan.png') }}" alt="Lapor Peninggalan" class="w-12 h-12" />
				</a>
				<span class="text-xs font-medium text-gray-700 text-center">{{ __('app.report_heritage') }}</span>
			</div>

			{{-- Peninggalan Terdekat --}}
			<div class="flex flex-col items-center">
				<a href="#" class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
					<img src="{{ asset('images/icons/peninggalan-terdekat.png') }}" alt="Peninggalan Terdekat" class="w-12 h-12" />
				</a>
				<span class="text-xs font-medium text-gray-700 text-center">{{ __('app.nearby_heritage') }}</span>
			</div>

			{{-- Kritik & Saran --}}
			<div class="flex flex-col items-center">
				<a href="#" class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3">
					<img src="{{ asset('images/icons/kritik-dan-saran.png') }}" alt="Kritik & Saran" class="w-12 h-12" />
				</a>
				<span class="text-xs font-medium text-gray-700 text-center">{{ __('app.feedback_suggestion') }}</span>
			</div>

			{{-- Tanya AI --}}
			<div class="flex flex-col items-center">
				<a href="#" class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3 relative">
					<img src="{{ asset('images/icons/tanya-ai.png') }}" alt="Tanya AI" class="w-12 h-12" />
					<div class="absolute -bottom-1 -right-1 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
						<i class="fas fa-lock text-gray-800 text-xs"></i>
					</div>
				</a>
				<span class="text-xs font-medium text-gray-700 text-center">{{ __('app.ask_ai') }}</span>
			</div>

			{{-- Video Peninggalan --}}
			<div class="flex flex-col items-center">
				<a href="#" class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3 relative">
					<img src="{{ asset('images/icons/video-peninggalan.png') }}" alt="Video Peninggalan" class="w-12 h-12" />
					<div class="absolute -bottom-1 -right-1 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
						<i class="fas fa-lock text-gray-800 text-xs"></i>
					</div>
				</a>
				<span class="text-xs font-medium text-gray-700 text-center">{{ __('app.heritage_video') }}</span>
			</div>

			{{-- Game --}}
			<div class="flex flex-col items-center">
				<a href="#" class="flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow mb-3 relative">
					<img src="{{ asset('images/icons/game.png') }}" alt="Game" class="w-12 h-12" />
					<div class="absolute -bottom-1 -right-1 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
						<i class="fas fa-lock text-gray-800 text-xs"></i>
					</div>
				</a>
				<span class="text-xs font-medium text-gray-700 text-center">{{ __('app.game') }}</span>
			</div>
		</div>
	</div>

	{{-- Bottom Navigation --}}
	<x-bottom-nav />
</x-guest-layout>