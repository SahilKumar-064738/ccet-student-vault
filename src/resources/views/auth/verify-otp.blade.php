@extends('layouts.guest')

@section('content')
<div>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900">
            Verify Your Email
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            We've sent a 6-digit code to {{ session('email') ?? $email }}
        </p>
    </div>

    <form method="POST" action="{{ route('verify-otp') }}" class="space-y-6">
        @csrf

        <input type="hidden" name="email" value="{{ session('email') ?? $email }}">

        <!-- OTP Input -->
        <div>
            <label for="otp" class="block text-sm font-medium text-gray-700">Enter OTP</label>
            <input id="otp" name="otp" type="text" maxlength="6" required autofocus
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-center text-2xl tracking-widest focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('otp') border-red-500 @enderror">
            @error('otp')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Verify OTP
            </button>
        </div>

        <!-- Resend OTP -->
        <div class="text-center">
            <form method="POST" action="{{ route('verify-otp.resend') }}" class="inline">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') ?? $email }}">
                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-500">
                    Didn't receive the code? Resend
                </button>
            </form>
        </div>
    </form>
</div>
@endsection