<x-auth-layout>
    <x-slot name="title">
        Forgot Password
    </x-slot>

    <div class="mb-4 text-sm text-gray-600">
        Lupa password? Masukkan email akun Anda dan kami akan mengirimkan link untuk reset password.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-text-input id="email" placeholder="Email" class="mt-1 block w-full" type="email" name="email"
                :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <x-primary-button class="flex w-full justify-center">
                Kirim Link Reset Password
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
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold leading-6 text-blue-500 hover:text-blue-600">
            Buat akun
        </a>
    </x-slot>
</x-auth-layout>
