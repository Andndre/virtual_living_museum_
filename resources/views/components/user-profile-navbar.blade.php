<div {{ $attributes->class(['relative']) }}>
    <a href="{{ route('profile.edit') }}"
       class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/30 hover:border-white/50 transition-colors block">
        @if(auth()->user()->profile_photo)
            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Picture"
                 class="w-full h-full object-cover"/>
        @else
            <img src="{{ asset('images/placeholder/profile-picture.png') }}" alt="Profile Picture"
                 class="w-full h-full object-cover"/>
        @endif
    </a>
    @if(!auth()->user()->phone_number || !auth()->user()->address || !auth()->user()->date_of_birth)
        <span class="absolute -top-1 -right-1 block w-4 h-4 bg-red-500 rounded-full border-2 border-white"></span>
    @endif
</div>
