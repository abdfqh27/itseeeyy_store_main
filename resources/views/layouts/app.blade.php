<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>{{ config('app.name', 'Itseey Store') }} - @yield('title', 'Manajemen Skincare')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/itseeystore-favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('storage/itseeystore-favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    @if(Auth::guard('admin')->check())
        <!-- Main Layout -->
        <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
            <!-- Sidebar -->
            <div x-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" aria-hidden="true" x-cloak>
                <div
                    x-show="sidebarOpen"
                    x-transition:enter="transition-opacity ease-linear duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-600 bg-opacity-75"
                    @click="sidebarOpen = false">
                </div>

                <div
                    x-show="sidebarOpen"
                    x-transition:enter="transition ease-in-out duration-300 transform"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in-out duration-300 transform"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    class="relative flex flex-col flex-1 w-full max-w-xs bg-white">

                    <div class="absolute top-0 right-0 pt-2 -mr-12">
                        <button
                            @click="sidebarOpen = false"
                            class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile Sidebar Content -->
                    <div class="flex flex-col flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex items-center flex-shrink-0 px-4">
                            <img src="{{ asset('storage/itseeystore-logo.png') }}" alt="Itseey Store" class="h-8 w-32">
                        </div>
                        <nav class="flex-1 px-2 mt-5 space-y-1 bg-white">
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Dashboard
                            </a>
                            
                            {{-- Admin only navigation items --}}
                            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->role === 'admin')
                            <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('categories.*') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Kategori
                            </a>
                            <a href="{{ route('products.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('products.*') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Produk
                            </a>
                            <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('users.*') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                Kelola Akun
                            </a>
                            @endif
                            
                            {{-- Available for both admin and pegawai --}}
                            <a href="{{ route('stock-movements.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('stock-movements.*') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Perpindahan Stok
                            </a>
                            
                            {{-- Admin only - Reports --}}
                            @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->role === 'admin')
                            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('reports.*') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Laporan
                            </a>
                            @endif
                        </nav>
                    </div>
                    <div class="flex-shrink-0 p-4 border-t border-gray-200">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 overflow-hidden text-white rounded-full bg-primary">
                                {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700">{{ Auth::guard('admin')->user()->name }}</p>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-medium text-primary-dark hover:text-primary-darker">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Static sidebar for desktop -->
            <div class="hidden md:flex md:flex-shrink-0">
                <div class="flex flex-col w-64">
                    <div class="flex flex-col flex-1 min-h-0 bg-white border-r border-gray-200">
                        <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
                            <div class="flex items-center flex-shrink-0 px-4">
                                <img src="{{ asset('storage/itseeystore-logo.png') }}" alt="Itseey Store" class="h-8 w-32">
                            </div>
                            <nav class="flex-1 px-2 mt-8 space-y-2 bg-white">
                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Dashboard
                                </a>
                                
                                {{-- Admin only navigation items --}}
                                @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->role === 'admin')
                                <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('categories.*') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Kategori
                                </a>
                                <a href="{{ route('products.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('products.*') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Produk
                                </a>
                                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('users.*') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    Kelola Akun
                                </a>
                                @endif
                                
                                {{-- Available for both admin and pegawai --}}
                                <a href="{{ route('stock-movements.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('stock-movements.*') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    Perpindahan Stok
                                </a>
                                
                                {{-- Admin only - Reports --}}
                                @if(Auth::guard('admin')->user() && Auth::guard('admin')->user()->role === 'admin')
                                <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('reports.*') ? 'bg-primary bg-opacity-10 text-primary-darker font-semibold' : 'text-gray-600 hover:bg-pink-50 hover:text-primary' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Laporan
                                </a>
                                @endif
                            </nav>
                        </div>
                        <div class="flex items-center justify-between flex-shrink-0 p-4 border-t border-gray-200">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 overflow-hidden text-white rounded-full bg-primary">
                                    {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-700">{{ Auth::guard('admin')->user()->name }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="p-1 rounded-full text-primary-dark hover:text-primary-darker hover:bg-pink-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile header and content -->
            <div class="flex flex-col flex-1 w-0 overflow-hidden">
                <!-- Top bar -->
                <div class="relative z-10 flex flex-shrink-0 h-16 bg-white shadow md:hidden">
                    <button
                        @click="sidebarOpen = true"
                        class="px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center justify-center flex-1">
                        <h1 class="text-lg font-bold text-primary">Itseey Store</h1>
                    </div>
                </div>

                <!-- Main content -->
                <main class="relative flex-1 overflow-y-auto focus:outline-none">
                    <div class="py-6">
                        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('header', 'Dashboard')</h1>
                        </div>
                        <div class="px-4 mx-auto mt-4 max-w-7xl sm:px-6 lg:px-8">
                            <!-- Flash messages -->
                            @if(session('success'))
                                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm" role="alert">
                                    <div class="flex">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ session('success') }}</span>
                                    </div>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm" role="alert">
                                    <div class="flex">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ session('error') }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Content -->
                            <div class="py-4">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    @else
        <div class="min-h-screen bg-gray-50">
            @yield('content')
        </div>
    @endif
</body>
</html>
