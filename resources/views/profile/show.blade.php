@extends('layouts.app')

@section('title', 'Мой профиль - Nekomi')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Profile Header -->
    <div class="glass-card rounded-2xl overflow-hidden mb-8">
        <div class="h-48 bg-gradient-to-r from-blue-900/50 to-cyan-900/50 relative">
            @if($user->banner)
                <img src="{{ Storage::url($user->banner) }}" class="w-full h-full object-cover absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            @else
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            @endif
        </div>
        <div class="px-8 pb-8 relative">
            <div class="flex flex-col md:flex-row items-end -mt-12 gap-6">
                <div class="w-32 h-32 rounded-2xl bg-zinc-900 border-4 border-zinc-900 overflow-hidden shadow-xl relative group">
                    <img src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D8ABC&color=fff' }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-grow mb-2">
                    <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                    <p class="text-zinc-400 text-sm">Пользователь с {{ $user->created_at->format('M Y') }}</p>
                </div>
                <div class="flex gap-3 mb-2">
                    <button onclick="document.getElementById('settings-modal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Настройки
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="glass hover:bg-red-500/20 text-red-400 hover:text-red-300 px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Выйти
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Stats -->
        <div class="space-y-6">
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-white font-bold mb-4">Статистика</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-zinc-900/50 p-4 rounded-xl text-center">
                        <div class="text-2xl font-bold text-blue-400">{{ $favorites->count() }}</div>
                        <div class="text-xs text-zinc-500 uppercase tracking-wider mt-1">Избранных</div>
                    </div>
                    <!-- Other stats can be calculated later -->
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Favorites -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4">Моя коллекция</h3>
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
                    <p class="text-zinc-500">Ты еще не добавил ни одного аниме в свою коллекцию.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div id="settings-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="document.getElementById('settings-modal').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="glass-card p-6 rounded-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white">Настройки профиля</h2>
                <button onclick="document.getElementById('settings-modal').classList.add('hidden')" class="text-zinc-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            @if (session('status'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-3 rounded-lg mb-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-lg mb-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-zinc-400 text-sm font-medium mb-2">Никнейм</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-zinc-900/50 border border-zinc-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label class="block text-zinc-400 text-sm font-medium mb-2">Аватар</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full bg-zinc-900/50 border border-zinc-700 rounded-lg px-4 py-2 text-zinc-300 text-sm focus:outline-none focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500">
                </div>

                <div>
                    <label class="block text-zinc-400 text-sm font-medium mb-2">Баннер</label>
                    <input type="file" name="banner" accept="image/*" class="w-full bg-zinc-900/50 border border-zinc-700 rounded-lg px-4 py-2 text-zinc-300 text-sm focus:outline-none focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500">
                </div>

                <div class="pt-4 border-t border-white/10">
                    <label class="flex items-center gap-3 cursor-pointer mb-4">
                        <input type="checkbox" name="is_collection_public" value="1" {{ $user->is_collection_public ? 'checked' : '' }} class="w-5 h-5 rounded bg-zinc-900 border-zinc-700 text-blue-600 focus:ring-blue-500 focus:ring-offset-zinc-900">
                        <span class="text-zinc-300 text-sm">Показывать коллекцию публично</span>
                    </label>

                    <label class="block text-zinc-400 text-sm font-medium mb-2">Новый пароль (необязательно)</label>
                    <input type="password" name="password" class="w-full bg-zinc-900/50 border border-zinc-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition-colors mb-2" placeholder="Новый пароль">
                    <input type="password" name="password_confirmation" class="w-full bg-zinc-900/50 border border-zinc-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="Подтвердите новый пароль">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white py-2 rounded-lg font-medium shadow-lg shadow-blue-500/20 transition-all">Сохранить изменения</button>
            </form>

            <div class="border-t border-white/10 mt-6 pt-6">
                <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить свой аккаунт? Это действие необратимо.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-500 py-2 rounded-lg text-sm font-medium transition-colors">Удалить аккаунт</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if (session('status') || $errors->any())
<script>
    document.getElementById('settings-modal').classList.remove('hidden');
</script>
@endif

@endsection
