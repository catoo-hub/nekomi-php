@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="relative rounded-3xl overflow-hidden border border-white/10 bg-zinc-900/30 mb-16 group backdrop-blur-sm">
        <div class="absolute inset-0">
            <img src="https://images5.alphacoders.com/133/1337432.jpeg" alt="Hero" class="w-full h-full object-cover opacity-50 group-hover:scale-105 transition-transform duration-700 ease-out">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/50 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-950/30 to-transparent"></div>
        </div>
        
        <div class="relative z-10 p-8 md:p-16 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-medium mb-6 backdrop-blur-md">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                New Episode Available
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6 text-white">
                Frieren: <br>
                <span class="text-gradient">Beyond Journey's End</span>
            </h1>
            
            <p class="text-lg text-zinc-300 mb-8 leading-relaxed max-w-2xl drop-shadow-md">
                The Demon King has been defeated, and the victorious hero party returns home before disbanding. Witness the journey that begins after the adventure ends.
            </p>
            
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('anime.show', ['id' => 'frieren']) }}" class="bg-white text-black hover:bg-zinc-200 px-8 py-3 rounded-lg font-semibold transition-all flex items-center gap-2 shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_30px_rgba(255,255,255,0.2)]">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" /></svg>
                    Watch S1 E1
                </a>
                <button class="glass hover:bg-white/10 text-white px-8 py-3 rounded-lg font-semibold transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add to List
                </button>
            </div>
        </div>
    </div>

    <!-- Filters & Heading -->
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-white tracking-tight">Trending Now</h2>
            <p class="text-zinc-400 text-sm mt-1">Top rated anime this week</p>
        </div>
        
        <form action="{{ route('home') }}" method="GET" class="flex flex-wrap gap-3">
            <!-- Sort Filter -->
            <div class="relative">
                <select name="sort" onchange="this.form.submit()" class="appearance-none glass-card text-zinc-300 rounded-lg pl-4 pr-10 py-2 text-sm focus:outline-none focus:border-blue-500/50 cursor-pointer hover:text-white transition-colors">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort by: Newest</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Sort by: Popular</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Sort by: Rating</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-zinc-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach ($animeList as $anime)
            <a href="{{ route('anime.show', ['id' => $anime['id']]) }}" class="group relative glass-card rounded-xl overflow-hidden hover:border-blue-500/30 transition-all duration-300 hover:-translate-y-1 cursor-pointer block">
                <div class="aspect-[3/4] relative overflow-hidden">
                    <img src="https://cdn.aniliberty.top{{ $anime['poster']['optimized']['preview'] ?? 'https://via.placeholder.com/300x450' }}" alt="Cover" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-transparent to-transparent opacity-90"></div>
                    
                    <div class="absolute top-3 right-3">
                        <div class="bg-zinc-950/60 backdrop-blur-md border border-white/10 text-white text-xs font-bold px-2 py-1 rounded-md flex items-center gap-1">
                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ $anime['added_in_users_favorites'] ?? 0 }}
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <h3 class="font-semibold text-white text-lg leading-tight mb-1 truncate group-hover:text-blue-400 transition-colors">{{ $anime['name']['main'] ?? 'Unknown Title' }}</h3>
                    <div class="flex items-center justify-between text-xs text-zinc-500 mt-2">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $anime['episodes_total'] ?? '?' }} eps
                        </span>
                        <span class="border border-zinc-800 rounded px-1.5 py-0.5 text-zinc-400">{{ $anime['type']['description'] ?? 'TV' }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-16 flex justify-center">
        <nav class="flex items-center gap-2 glass-card p-1.5 rounded-lg">
            @if(isset($meta['pagination']))
                @php
                    $pagination = $meta['pagination'];
                    $currentPage = $pagination['current_page'];
                    $totalPages = $pagination['total_pages'];
                    $prevPage = $currentPage > 1 ? $currentPage - 1 : null;
                    $nextPage = $currentPage < $totalPages ? $currentPage + 1 : null;
                @endphp
                
                <a href="{{ $prevPage ? route('home', array_merge(request()->query(), ['page' => $prevPage])) : '#' }}" class="w-9 h-9 flex items-center justify-center rounded-md text-zinc-400 hover:bg-white/5 hover:text-white transition-all {{ !$prevPage ? 'pointer-events-none opacity-50' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <a href="#" class="w-9 h-9 flex items-center justify-center rounded-md bg-blue-600 text-white font-medium shadow-lg shadow-blue-500/20">{{ $currentPage }}</a>
                
                <a href="{{ $nextPage ? route('home', array_merge(request()->query(), ['page' => $nextPage])) : '#' }}" class="w-9 h-9 flex items-center justify-center rounded-md text-zinc-400 hover:bg-white/5 hover:text-white transition-all {{ !$nextPage ? 'pointer-events-none opacity-50' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @else
                @php
                    $currentPage = request('page', 1);
                    $prevPage = max(1, $currentPage - 1);
                    $nextPage = $currentPage + 1;
                @endphp
                
                <a href="{{ route('home', array_merge(request()->query(), ['page' => $prevPage])) }}" class="w-9 h-9 flex items-center justify-center rounded-md text-zinc-400 hover:bg-white/5 hover:text-white transition-all {{ $currentPage == 1 ? 'pointer-events-none opacity-50' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <a href="#" class="w-9 h-9 flex items-center justify-center rounded-md bg-blue-600 text-white font-medium shadow-lg shadow-blue-500/20">{{ $currentPage }}</a>
                
                <a href="{{ route('home', array_merge(request()->query(), ['page' => $nextPage])) }}" class="w-9 h-9 flex items-center justify-center rounded-md text-zinc-400 hover:bg-white/5 hover:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @endif
        </nav>
    </div>
@endsection
