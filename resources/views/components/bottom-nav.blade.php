{{-- Bottom Navigation Component --}}
<nav class="fixed bottom-0 left-0 right-0 flex justify-center z-50">
	<div class="flex justify-center items-start py-3 px-3 w-full lg:max-w-xl mx-auto bg-white border-t border-gray-200 relative shadow-black shadow-lg">
		{{-- Left Navigation Items --}}
		<div class="flex flex-1 justify-around items-start pr-8">
			<a href="{{ route('guest.home') }}" class="flex flex-col items-center {{ request()->routeIs('guest.home') ? 'text-primary' : 'text-gray-400' }} min-w-0">
				<i class="fas fa-home text-lg h-8"></i>
				<span class="text-xs leading-tight">{{ __('app.home') }}</span>
			</a>
			<a href="{{ route('guest.panduan') }}" class="flex flex-col items-center {{ request()->routeIs('guest.panduan') ? 'text-primary' : 'text-gray-400' }} min-w-0">
				<i class="fas fa-book text-lg h-8"></i>
				<span class="text-xs leading-tight">{{ __('app.guide') }}</span>
			</a>
		</div>

		{{-- Center AR Marker Button --}}
		<div class="absolute left-1/2 transform -translate-x-1/2 -translate-y-8">
			<a href="{{ route('guest.ar-marker') }}" class="flex items-center justify-center w-16 h-16 bg-primary rounded-full border-4 border-primary/30 shadow-lg hover:shadow-xl transition-shadow">
				<img src="{{ asset('images/icons/ar-marker.svg') }}" alt="AR Marker" class="w-10 h-10 text-white" />
			</a>
		</div>

		{{-- Right Navigation Items --}}
		<div class="flex flex-1 justify-around items-start pl-8">
			<a href="{{ route('guest.pengaturan') }}" class="flex flex-col items-center {{ request()->routeIs('guest.pengaturan') ? 'text-primary' : 'text-gray-400' }} min-w-0">
				<i class="fas fa-cog text-lg h-8"></i>
				<span class="text-xs leading-tight">{{ __('app.settings') }}</span>
			</a>
			<a href="{{ route('guest.pengembang') }}" class="flex flex-col items-center {{ request()->routeIs('guest.pengembang') ? 'text-primary' : 'text-gray-400' }} min-w-0">
				<i class="fas fa-info-circle text-lg h-8"></i>
				<span class="text-xs leading-tight text-center">{{ __('app.developer_info') }}</span>
			</a>
		</div>
	</div>
</nav>
