<x-guest-layout>
	{{-- Header Section --}}
	<div class="px-6 py-6 bg-primary text-white">
        <div class="flex justify-between items-center">
			<a href="{{ route('guest.pengaturan') }}" class="w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-colors">
				<i class="fas fa-arrow-left text-white text-lg"></i>
			</a>
		</div>

		<div class="flex flex-col items-center">
			{{-- Profile Photo Section --}}
			<div class="relative mb-6">
				<div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white/30">
					@if(auth()->user()->profile_photo)
						<img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Picture" class="w-full h-full object-cover" />
					@else
						<img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="Profile Picture" class="w-full h-full object-cover" />
					@endif
				</div>
				{{-- Edit Button --}}
				<button type="button" class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:bg-gray-100 transition-colors" onclick="document.getElementById('profile-photo').click()">
					<i class="fas fa-edit text-primary text-lg"></i>
				</button>
				{{-- Hidden File Input --}}
				<input type="file" id="profile-photo" name="profile_photo" accept="image/*" class="hidden" onchange="handlePhotoUpload(this)">
			</div>

			<div class="text-center mb-8">
				<h1 class="text-xl font-bold">{{ __('app.profile') }}</h1>
				<p class="text-sm opacity-90">{{ __('app.manage_profile') }}</p>
			</div>
		</div>
	</div>

	{{-- Content Section --}}
	<div class="px-6 py-8 bg-gray-50 rounded-t-3xl -mt-6 relative">
		<div class="space-y-6">
			{{-- Update Profile Information --}}
			<div class="bg-white rounded-2xl p-6 shadow-sm">
				<div class="max-w-xl">
					@include('profile.partials.update-profile-information-form')
				</div>
			</div>

			{{-- Update Password --}}
			<div class="bg-white rounded-2xl p-6 shadow-sm">
				<div class="max-w-xl">
					@include('profile.partials.update-password-form')
				</div>
			</div>

			{{-- Delete Account --}}
			<div class="bg-white rounded-2xl p-6 shadow-sm">
				<div class="max-w-xl">
					@include('profile.partials.delete-user-form')
				</div>
			</div>
		</div>
	</div>

	<script>
		function handlePhotoUpload(input) {
			if (input.files && input.files[0]) {
				const file = input.files[0];
				const reader = new FileReader();
				
				reader.onload = function(e) {
					// Update the preview image
					const img = input.parentElement.querySelector('img');
					img.src = e.target.result;
				}
				
				reader.readAsDataURL(file);
				
				// Create FormData and send via AJAX
				const formData = new FormData();
				formData.append('profile_photo', file);
				formData.append('name', '{{ auth()->user()->name }}');
				formData.append('email', '{{ auth()->user()->email }}');
				formData.append('_token', '{{ csrf_token() }}');
				formData.append('_method', 'PATCH');
				
				fetch('{{ route("profile.update") }}', {
					method: 'POST',
					body: formData,
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json'
					}
				})
				.then(response => {
					// Log response for debugging
					console.log('Response status:', response.status);
					console.log('Response headers:', response.headers);
					
					if (!response.ok) {
						return response.text().then(text => {
							console.error('Response text:', text);
							throw new Error(`HTTP ${response.status}: ${response.statusText}`);
						});
					}
					
					// Check if response is JSON
					const contentType = response.headers.get('content-type');
					if (contentType && contentType.includes('application/json')) {
						return response.json();
					} else {
						return response.text().then(text => {
							console.error('Expected JSON but got:', text);
							throw new Error('Response is not JSON');
						});
					}
				})
				.then(data => {
					if (data.success) {
						// Show success message
						console.log('{{ __("app.photo_updated") }}');
						// You can add a toast notification here if needed
					} else {
						console.error('{{ __("app.upload_error") }}:', data);
					}
				})
				.catch(error => {
					console.error('{{ __("app.error_occurred") }}:', error);
				});
			}
		}
	</script>
</x-guest-layout>
