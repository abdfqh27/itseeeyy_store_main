@extends('layouts.app')

@section('title', 'Login | Itseey Store Admin')

@section('content')
<div class="min-h-screen flex flex-col justify-center bg-gradient-to-br from-pink-200 to-pink-50 py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="mt-3 text-center">
            <img src="{{ asset('storage/itseeystore-logo.png') }}" alt="Itseey Store" class="h-16 w-auto mx-auto">
        </div>
        <h2 class="mt-2 text-center text-lg text-pink-500">Admin Dashboard</h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-pink-50 py-8 px-4 shadow-lg border border-pink-200 sm:rounded-xl sm:px-10">
            <div class="text-center mb-6">
                <h3 class="text-xl font-semibold text-pink-800">Sign In</h3>
                <p class="text-sm text-pink-600 mt-1">Access your admin account</p>
            </div>

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    x-transition class="fixed inset-0 flex items-center justify-center z-50" role="alert">
                    <div class="bg-red-100 border border-red-300 text-red-800 px-6 py-4 rounded-lg shadow-xl relative w-full max-w-sm mx-auto">
                        <strong class="font-semibold block">Login Gagal!</strong>
                        <span class="block text-sm mt-1">{{ session('error') }}</span>
                        <button @click="show = false" class="absolute top-1 right-2 text-red-500 hover:text-red-700 text-lg leading-none">
                            &times;
                        </button>
                    </div>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('login') }}" method="POST">
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
                    <label for="password" class="block text-sm font-medium text-pink-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-pink-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="pl-10 appearance-none block w-full px-3 py-2 border border-pink-300 rounded-md shadow-sm placeholder-pink-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm bg-white"
                               placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 text-primary focus:ring-primary focus:ring-opacity-50 border-pink-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-pink-700">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-pink-500 hover:text-primary-dark transition-colors">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-500 hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors duration-200">
                        Sign in to dashboard
                    </button>
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

        <div class="mt-4 text-center text-xs text-pink-500">
            &copy; {{ date('Y') }} Itseey Store. All rights reserved.
        </div>
    </div>
</div>
@endsection
