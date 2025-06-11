@extends('layouts.app')

@section('title', 'Forgot Password | Itseey Store')

@section('content')
<div class="min-h-screen flex flex-col justify-center bg-gradient-to-br from-pink-200 to-pink-50 py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <svg class="h-12 w-auto text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
        </div>
        <h1 class="mt-3 text-3xl font-extrabold text-center text-primary">Itseey Store</h1>
        <h2 class="mt-2 text-center text-lg text-pink-700">Reset Password</h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-pink-50 py-8 px-4 shadow-lg border border-pink-200 sm:rounded-xl sm:px-10">
            <div class="text-center mb-6">
                <h3 class="text-xl font-semibold text-pink-800">Recover Your Account</h3>
                <p class="text-sm text-pink-600 mt-1">Enter your email to reset your password</p>
            </div>

            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-pink-700">Email address</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-pink-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                               class="pl-10 appearance-none block w-full px-3 py-2 border border-pink-300 rounded-md shadow-sm placeholder-pink-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm bg-white"
                               placeholder="your.email@example.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-500 hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors duration-200">
                        Reset Password
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center font-medium text-primary hover:text-primary-dark text-sm transition-colors">
                        <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to login
                    </a>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-pink-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-pink-50 text-pink-600"></span>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center text-xs text-pink-500">
            &copy; {{ date('Y') }} Itseey Store.
        </div>
    </div>
</div>
@endsection
