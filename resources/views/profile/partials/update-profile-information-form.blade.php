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
            <x-input-label for="name" :value="__('app.name')"/>
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                          required autofocus autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
        </div>

        <div>
            <x-input-label for="email" :value="__('app.email')"/>
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', $user->email)" required autocomplete="username"/>
            <x-input-error class="mt-2" :messages="$errors->get('email')"/>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('app.email_unverified') }}

                        <button form="send-verification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
            <x-input-label for="phone_number" :value="__('app.phone')"/>
            <x-text-input
                id="phone_number"
                name="phone_number"
                type="text"
                class="mt-1 block w-full {{ !auth()->user()->phone_number ? 'border-yellow-300 bg-yellow-50' : '' }}"
                :value="old('phone_number', $user->phone_number)"
                autocomplete="tel"
            />
            @if(!auth()->user()->phone_number)
                <p class="mt-1 text-xs text-yellow-600">{{ __('app.please_enter_your_phone_number') }}</p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')"/>
        </div>

        <div>
            <x-input-label for="address" :value="__('app.address')"/>
            <x-text-input
                id="address"
                name="address"
                type="text"
                class="mt-1 block w-full {{ !auth()->user()->address ? 'border-yellow-300 bg-yellow-50' : '' }}"
                :value="old('address', $user->address)"
                autocomplete="street-address"
            />
            @if(!auth()->user()->address)
                <p class="mt-1 text-xs text-yellow-600">{{ __('app.please_enter_your_address') }}</p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('address')"/>
        </div>

        <div>
            <x-input-label for="date_of_birth" :value="__('app.date_of_birth')"/>
            <div class="relative">
                <x-text-input
                    id="date_of_birth"
                    name="date_of_birth"
                    type="text"
                    class="mt-1 block w-full date-picker {{ !auth()->user()->date_of_birth ? 'border-yellow-300 bg-yellow-50' : '' }}"
                    :value="old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '')"
                    autocomplete="off"
                    placeholder="YYYY-MM-DD"
                    readonly
                    onfocus="this.blur()"
                />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            @if(!auth()->user()->date_of_birth)
                <p class="mt-1 text-xs text-yellow-600">{{ __('app.please_enter_your_date_of_birth') }}</p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')"/>
        </div>

        <div>
            <x-input-label for="pekerjaan" :value="__('app.job')"/>
            <x-text-input
                id="pekerjaan"
                name="pekerjaan"
                type="text"
                class="mt-1 block w-full {{ !auth()->user()->pekerjaan ? 'border-yellow-300 bg-yellow-50' : '' }}"
                :value="old('pekerjaan', $user->pekerjaan)"
            />
            @if(!auth()->user()->pekerjaan)
                <p class="mt-1 text-xs text-yellow-600">{{ __('app.please_enter_your_job') }}</p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('pekerjaan')"/>
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
