<x-auth-layout>
    <x-slot name="title">
        Reset Password
    </x-slot>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-text-input id="email" placeholder="Email" class="mt-1 block w-full" type="email" name="email"
                :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-text-input id="password" placeholder="Password" class="mt-1 block w-full" type="password"
                name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-text-input id="password_confirmation" placeholder="Confirm Password" class="mt-1 block w-full"
                type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <x-primary-button class="flex w-full justify-center">
                Reset Password
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <a class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                href="{{ route('login') }}">
                Kembali ke Login
            </a>
        </div>
    </form>

    <x-slot name="footer">
        Sudah ingat password?
        <a href="{{ route('login') }}" class="font-semibold leading-6 text-blue-500 hover:text-blue-600">
            Log in
        </a>
    </x-slot>
</x-auth-layout>
