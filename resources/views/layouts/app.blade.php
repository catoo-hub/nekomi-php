<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Nekomi - Web3 Anime Experience')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/playerjs.js') }}"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(9, 9, 11, 0.4); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-card { background: rgba(24, 24, 27, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .text-gradient { background: linear-gradient(to right, #60a5fa, #3b82f6, #22d3ee); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        /* Animated Background */
        .blob { position: absolute; filter: blur(80px); opacity: 0.4; z-index: -1; animation: float 10s infinite ease-in-out alternate; }
        @keyframes float { 0% { transform: translate(0, 0) rotate(0deg); } 100% { transform: translate(20px, 40px) rotate(10deg); } }
    </style>
    @stack('styles')
</head>
<body class="bg-zinc-950 text-zinc-50 antialiased selection:bg-blue-500 selection:text-white min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Background Gradients -->
    <div class="fixed inset-0 z-[-1] pointer-events-none">
        <div class="blob bg-blue-600 w-[500px] h-[500px] rounded-full top-[-10%] left-[-10%]"></div>
        <div class="blob bg-cyan-600 w-[400px] h-[400px] rounded-full bottom-[-10%] right-[-10%] animation-delay-2000"></div>
        <div class="blob bg-violet-600 w-[300px] h-[300px] rounded-full top-[40%] left-[30%] opacity-20 animation-delay-4000"></div>
    </div>

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass border-b-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2 cursor-pointer group">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 transition-all">
                        N
                    </div>
                    <span class="text-xl font-bold tracking-tight group-hover:text-blue-400 transition-colors">Nekomi</span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ url('/') }}" class="px-4 py-2 text-sm font-medium text-zinc-300 hover:text-white hover:bg-white/5 rounded-md transition-all">Discover</a>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-zinc-300 hover:text-white hover:bg-white/5 rounded-md transition-all">Trending</a>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-zinc-300 hover:text-white hover:bg-white/5 rounded-md transition-all">Collections</a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-4">
                    <!-- Search Input -->
                    <form action="{{ route('search') }}" method="GET" class="hidden md:flex relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-zinc-500 group-focus-within:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}"
                            class="bg-zinc-900/50 border border-zinc-800 text-zinc-200 text-sm rounded-md focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 block w-64 pl-10 p-1.5 placeholder-zinc-500 transition-all focus:bg-zinc-900/80 outline-none" 
                            placeholder="Search anime...">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <span class="text-xs border border-zinc-700 rounded px-1.5 py-0.5 text-zinc-500">⌘K</span>
                        </div>
                    </form>

                    <!-- Auth -->
                    <div class="flex items-center gap-2">
                        @auth
                            <a href="{{ url('/profile') }}" class="text-sm font-medium text-zinc-400 hover:text-white transition-colors">Profile</a>
                        @else
                            <a href="{{ url('/login') }}" class="text-sm font-medium text-zinc-400 hover:text-white transition-colors px-3 py-2">Log in</a>
                            <a href="{{ url('/register') }}" class="text-sm font-medium bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md transition-all shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40">Get Started</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full z-10">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-zinc-800 bg-zinc-950/50 backdrop-blur-xl py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-cyan-600 rounded flex items-center justify-center text-white text-xs font-bold">N</div>
                <span class="text-zinc-400 text-sm">© 2025 Nekomi Inc.</span>
            </div>
            <div class="flex gap-6 text-sm text-zinc-500">
                <a href="#" class="hover:text-blue-400 transition-colors">Privacy</a>
                <a href="#" class="hover:text-blue-400 transition-colors">Terms</a>
                <a href="#" class="hover:text-blue-400 transition-colors">Twitter</a>
                <a href="#" class="hover:text-blue-400 transition-colors">Discord</a>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
