<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="antialiased">
    <header id="header" class="w-full transition-all duration-300 h-100 md:flex justify-between bg-[rgba(22,34,57,0.95)] text-white tracking-wide">
        <nav class="container mx-auto p-4 flex justify-between items-center">
            <p class="text-3xl font-bold ml-4"><span class="text-[#f5a425]">EM</span>PATHY</p>
            <!-- Hamburger Button for Mobile -->
            <button id="menu-btn" class="block md:hidden md:focus:outline-none">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </nav>
        <!-- Navigation Menu -->
        <ul id="menu" class="hidden md:flex md:space-x-2 md:mr-4">
            <li class="w-full md:w-36 flex justify-center hover:bg-[#f5a425] focus:bg-[#f5a425] active:bg-[#f5a425] md:hover:bg-transparent md:focus:bg-transparent md:active:bg-transparent">
                <a href="#about" class="border-2 p-4 border-transparent my-auto transition-colors duration-300 md:hover:border-[#f5a425] md:focus:border-[#f5a425] md:active:border-[#f5a425]">
                    ABOUT US
                </a>
            </li>
            <li class="w-full md:w-36 flex justify-center hover:bg-[#f5a425] focus:bg-[#f5a425] active:bg-[#f5a425] md:hover:bg-transparent md:focus:bg-transparent md:active:bg-transparent">
                <a href="#contact" class="border-2 p-4 border-transparent my-auto transition-colors duration-300 md:hover:border-[#f5a425] md:focus:border-[#f5a425] md:active:border-[#f5a425]">
                    CONTACT US
                </a>
            </li>
            <li class="w-full md:w-36 flex justify-center hover:bg-[#f5a425] focus:bg-[#f5a425] active:bg-[#f5a425] md:hover:bg-transparent md:focus:bg-transparent md:active:bg-transparent">
                <a href="#advertisements" class="border-2 p-4 border-transparent my-auto transition-colors duration-300 md:hover:border-[#f5a425] md:focus:border-[#f5a425] md:active:border-[#f5a425]">
                    ADVERTISEMENT
                </a>
            </li>
            <li class="w-full md:w-36 flex justify-center hover:bg-[#f5a425] focus:bg-[#f5a425] active:bg-[#f5a425] md:hover:bg-transparent md:focus:bg-transparent md:active:bg-transparent">
                <a href="#patrons" class="border-2 p-4 border-transparent my-auto transition-colors duration-300 md:hover:border-[#f5a425] md:focus:border-[#f5a425] md:active:border-[#f5a425]">
                    PATRONS
                </a>
            </li>
            <li class="w-full md:w-36 flex justify-center hover:bg-[#f5a425] focus:bg-[#f5a425] active:bg-[#f5a425] md:hover:bg-transparent md:focus:bg-transparent md:active:bg-transparent">
                <a href="#listeners" class="border-2 p-4 border-transparent my-auto transition-colors duration-300 md:hover:border-[#f5a425] md:focus:border-[#f5a425] md:active:border-[#f5a425]">
                    LISTENERS
                </a>
            </li>
        </ul>
    </header>
    @livewire('site-header')
    @livewire('about-us')
    <div class="w-full md:flex px-2">
        <div class="md:w-3/4 w-full">
            @livewire('online-listener')
        </div>
        <div class="md:w-1/4 w-full">
            @livewire('advertisements')
        </div>
    </div>
    @livewire('listeners')
    @livewire('patrons')
    @livewire('contact-us')
    @livewire('site-footer')
</body>
    @livewireScripts
    @stack('scripts')
</html>
