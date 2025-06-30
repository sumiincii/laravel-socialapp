<x-guest-layout>
    <div class="flex flex-col items-center justify-center mt-6 space-y-4">
        <!-- Custom Logo -->
        <div>
            <a href="/">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-16 w-16 rounded-full shadow-lg">
            </a>
        </div>

        <!-- Welcome Text -->
        <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-100">
            Welcome Back!
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Please sign in to your account
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 mt-6" :status="session('status')" />

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="mt-4 space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" 
                          :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-2">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:underline dark:text-blue-400"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="flex flex-col items-center space-y-2 mt-4">
            <x-primary-button class="w-full justify-center">
                {{ __('Log in') }}
            </x-primary-button>

            <!-- Register Link -->
            <p class="text-sm text-center text-gray-600 dark:text-gray-400">
                <br>
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline dark:text-blue-400">
                    Don't have an account?
                    Register here
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
