<x-guest-layout>
    <!-- Session Status -->
    <h4 class="mb-2">Welcome👋</h4>
    <p class="mb-4">Please sign-in to your account and start the adventure</p>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <!-- Password -->
        <div class="mt-4">
            <div class="d-flex justify-content-between">
            <x-input-label for="password" :value="__('Password')" />
            <a href="{{ route('password.request') }}">
                      <small>Forgot Password?</small>
            </a>
            </div>
            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />


            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    <p class="text-center">
        <span>New on our platform?</span>
        <a href="{{route('register')}}">
            <span>Create an account</span>
        </a>
    </p>
</x-guest-layout>
