<x-auth-layout>
    <x-slot name="title">
        Welcome!
    </x-slot>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-text-input id="name" placeholder="Name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-text-input id="email" placeholder="Email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-text-input id="password" 
                            placeholder="Password"
                            class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-text-input id="password_confirmation" 
                            placeholder="Confirm Password" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            
            <x-primary-button class="w-full flex justify-center">
                Register
            </x-primary-button>
        </div>
    </form>

    <x-slot name="footer">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold leading-6 text-blue-500 hover:text-blue-600">
            Log in
        </a>
    </x-slot>
</x-auth-layout>
