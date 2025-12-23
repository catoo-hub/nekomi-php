@extends('layouts.app')

@section('title', 'My Profile - Nekomi')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Profile Header -->
    <div class="glass-card rounded-2xl overflow-hidden mb-8">
        <div class="h-48 bg-gradient-to-r from-blue-900/50 to-cyan-900/50 relative">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        </div>
        <div class="px-8 pb-8 relative">
            <div class="flex flex-col md:flex-row items-end -mt-12 gap-6">
                <div class="w-32 h-32 rounded-2xl bg-zinc-900 border-4 border-zinc-900 overflow-hidden shadow-xl">
                    <img src="https://ui-avatars.com/api/?name=User&background=0D8ABC&color=fff" class="w-full h-full object-cover">
                </div>
                <div class="flex-grow mb-2">
                    <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                    <p class="text-zinc-400 text-sm">Member since {{ $user->created_at->format('M Y') }}</p>
                </div>
                <div class="flex gap-3 mb-2">
                    <button class="glass hover:bg-white/10 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">Edit Profile</button>
                    <button class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-blue-500/20 transition-all">Settings</button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Stats -->
        <div class="space-y-6">
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-white font-bold mb-4">Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-zinc-900/50 p-4 rounded-xl text-center">
                        <div class="text-2xl font-bold text-blue-400">{{ $favorites->count() }}</div>
                        <div class="text-xs text-zinc-500 uppercase tracking-wider mt-1">Favorites</div>
                    </div>
                    <!-- Other stats can be calculated later -->
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Favorites -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4">My Collection</h3>
                @if($favorites->count() > 0)
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-4">
                        @foreach($favorites as $fav)
                        <a href="{{ route('anime.show', $fav->anime_id) }}" class="aspect-[3/4] rounded-xl overflow-hidden relative group cursor-pointer block">
                            <img src="{{ $fav->poster_url ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                                <span class="text-white text-sm font-medium truncate w-full">{{ $fav->title }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-zinc-500">You haven't added any anime to your collection yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
