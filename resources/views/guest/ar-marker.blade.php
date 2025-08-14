<x-guest-layout>
	{{-- Header Section with Back Button --}}
	<div class="px-6 py-6 bg-primary text-white">
		<div class="flex items-center space-x-4">
			<a href="{{ route('guest.home') }}" class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
				<i class="fas fa-arrow-left text-white"></i>
			</a>
			<div>
				<h1 class="text-xl font-bold">AR Scanner</h1>
				<p class="text-sm opacity-90">Scan objek peninggalan sejarah</p>
			</div>
		</div>
	</div>

	{{-- Camera/AR Section --}}
	<div class="flex-1 bg-black relative">
		{{-- AR Camera Placeholder --}}
		<div class="h-screen flex items-center justify-center text-white">
			<div class="text-center">
				<div class="w-32 h-32 mx-auto mb-6 rounded-full bg-white/10 flex items-center justify-center">
					<i class="fas fa-camera text-4xl text-white/70"></i>
				</div>
				<h3 class="text-xl font-semibold mb-2">Arahkan Kamera</h3>
				<p class="text-white/80 text-sm px-8">
					Arahkan kamera ke objek peninggalan sejarah untuk melihat informasi AR
				</p>
			</div>
		</div>

		{{-- AR Overlay Controls --}}
		<div class="absolute top-6 right-6">
			<button class="w-12 h-12 bg-black/50 rounded-full flex items-center justify-center text-white">
				<i class="fas fa-info-circle"></i>
			</button>
		</div>

		{{-- Bottom Controls --}}
		<div class="absolute bottom-8 left-0 right-0">
			<div class="flex justify-center space-x-6">
				{{-- Flash Toggle --}}
				<button class="w-14 h-14 bg-black/50 rounded-full flex items-center justify-center text-white">
					<i class="fas fa-bolt"></i>
				</button>
				
				{{-- Capture Button --}}
				<button class="w-20 h-20 bg-white/20 border-4 border-white rounded-full flex items-center justify-center">
					<div class="w-16 h-16 bg-white rounded-full"></div>
				</button>
				
				{{-- Gallery --}}
				<button class="w-14 h-14 bg-black/50 rounded-full flex items-center justify-center text-white">
					<i class="fas fa-images"></i>
				</button>
			</div>
		</div>

		{{-- AR Target Frame --}}
		<div class="absolute inset-0 flex items-center justify-center pointer-events-none">
			<div class="w-64 h-64 border-2 border-primary rounded-2xl relative">
				<div class="absolute -top-1 -left-1 w-6 h-6 border-l-4 border-t-4 border-primary rounded-tl-lg"></div>
				<div class="absolute -top-1 -right-1 w-6 h-6 border-r-4 border-t-4 border-primary rounded-tr-lg"></div>
				<div class="absolute -bottom-1 -left-1 w-6 h-6 border-l-4 border-b-4 border-primary rounded-bl-lg"></div>
				<div class="absolute -bottom-1 -right-1 w-6 h-6 border-r-4 border-b-4 border-primary rounded-br-lg"></div>
				
				<div class="absolute inset-0 flex items-center justify-center">
					<div class="text-center text-white">
						<i class="fas fa-crosshairs text-2xl mb-2 opacity-50"></i>
						<p class="text-sm opacity-75">Fokuskan objek di sini</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-guest-layout>
