<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('app.profile_information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('app.update_profile_desc') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('app.name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('app.email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('app.email_unverified') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('app.resend_verification') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('app.verification_sent') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        
        <div>
            <x-input-label for="phone_number" :value="__('Nomor Telepon')" />
            <x-text-input 
                id="phone_number" 
                name="phone_number" 
                type="text" 
                class="mt-1 block w-full {{ !auth()->user()->phone_number ? 'border-yellow-300 bg-yellow-50' : '' }}" 
                :value="old('phone_number', $user->phone_number)" 
                placeholder="Masukkan nomor telepon Anda"
                autocomplete="tel" 
            />
            @if(!auth()->user()->phone_number)
                <p class="mt-1 text-xs text-yellow-600">Mohon lengkapi nomor telepon Anda</p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Alamat')" />
            <x-text-input 
                id="address" 
                name="address" 
                type="text" 
                class="mt-1 block w-full {{ !auth()->user()->address ? 'border-yellow-300 bg-yellow-50' : '' }}" 
                :value="old('address', $user->address)" 
                placeholder="Masukkan alamat lengkap Anda"
                autocomplete="street-address" 
            />
            @if(!auth()->user()->address)
                <p class="mt-1 text-xs text-yellow-600">Mohon lengkapi alamat Anda</p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div>
            <x-input-label for="date_of_birth" :value="__('Tanggal Lahir')" />
            <x-text-input 
                id="date_of_birth" 
                name="date_of_birth" 
                type="date" 
                class="mt-1 block w-full {{ !auth()->user()->date_of_birth ? 'border-yellow-300 bg-yellow-50' : '' }}" 
                :value="old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '')" 
                autocomplete="bday"
                placeholder="YYYY-MM-DD" 
            />
            @if(!auth()->user()->date_of_birth)
                <p class="mt-1 text-xs text-yellow-600">Mohon lengkapi tanggal lahir Anda</p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="w-full flex justify-center">{{ __('app.save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('app.saved') }}</p>
            @endif
        </div>
    </form>
</section>
