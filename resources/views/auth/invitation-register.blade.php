<x-guest-layout>
    <form method="POST" action="{{ route('invitation.accept',['token'=>$invitation->token]) }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

       

        <!-- Email Address -->
        
        <x-text-input id="email" class="form-control" type="hidden" name="email" value="{{ $invitation->email }}" required autocomplete="username" />
            
        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="form-control"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Tenant Name -->
            <x-text-input id="tenant_name" class="form-control" type="hidden" name="tenant_name" value="{{ $invitation->tenant_id}}" required autofocus autocomplete="name" />
        <div class="flex items-center justify-end mt-4">
            
            <x-primary-button class="ms-4 ">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
