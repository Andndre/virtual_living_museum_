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
			<h1 class="text-xl font-bold">{{ __('app.app_guide') }}</h1>
			<p class="text-sm opacity-90">{{ __('app.learn_how_to_use') }}</p>
		</div>
	</div>

	{{-- Content Section --}}
	<div class="px-6 py-8 pb-24 bg-gray-50 rounded-t-3xl -mt-6 relative">
		<div class="space-y-4">
			{{-- Guide Item 1 --}}
			<div class="bg-white rounded-2xl p-4 shadow-sm flex items-center">
				<div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mr-4">
					<i class="fas fa-sign-in-alt text-white text-lg"></i>
				</div>
				<div class="flex-1">
					<h3 class="text-base font-semibold text-gray-800">{{ __('app.guide_step_1') }}</h3>
				</div>
			</div>

			{{-- Guide Item 2 --}}
			<div class="bg-white rounded-2xl p-4 shadow-sm flex items-center">
				<div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mr-4">
					<i class="fas fa-users text-white text-lg"></i>
				</div>
				<div class="flex-1">
					<h3 class="text-base font-semibold text-gray-800">{{ __('app.guide_step_2') }}</h3>
				</div>
			</div>

			{{-- Guide Item 3 --}}
			<div class="bg-white rounded-2xl p-4 shadow-sm flex items-center">
				<div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mr-4">
					<i class="fas fa-bars text-white text-lg"></i>
				</div>
				<div class="flex-1">
					<h3 class="text-base font-semibold text-gray-800">{{ __('app.guide_step_3') }}</h3>
				</div>
			</div>

			{{-- Guide Item 4 --}}
			<div class="bg-white rounded-2xl p-4 shadow-sm flex items-start">
				<div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mr-4 mt-1">
					<i class="fas fa-search text-white text-lg"></i>
				</div>
				<div class="flex-1">
					<h3 class="text-base font-semibold text-gray-800 mb-1">{{ __('app.guide_step_4') }}</h3>
				</div>
			</div>

			{{-- Guide Item 5 --}}
			<div class="bg-white rounded-2xl p-4 shadow-sm flex items-start">
				<div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mr-4 mt-1">
					<i class="fas fa-camera text-white text-lg"></i>
				</div>
				<div class="flex-1">
					<h3 class="text-base font-semibold text-gray-800 mb-1">{{ __('app.guide_step_5') }}</h3>
				</div>
			</div>
		</div>
	</div>

	{{-- Bottom Navigation --}}
	<x-bottom-nav />
</x-guest-layout>
