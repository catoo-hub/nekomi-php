@extends('layouts.app')

@section('title', 'Коллекции - Nekomi')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-white mb-8">Коллекции пользователей</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($users as $user)
            <div class="glass-card p-6 rounded-2xl flex flex-col items-center">
                <!-- User Info -->
                <a href="{{ route('collections.show', $user->id) }}" class="flex flex-col items-center mb-6 group">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-zinc-700 group-hover:border-blue-500 transition-colors mb-3">
                        <img src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D8ABC&color=fff' }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-white font-bold text-lg group-hover:text-blue-400 transition-colors">{{ $user->name }}</h3>
                    <span class="text-zinc-500 text-xs">{{ $user->favorites_count ?? $user->favorites()->count() }} аниме</span>
                </a>

                <!-- Cards Stack -->
                <div class="relative w-40 h-56 cursor-pointer group perspective-1000" onclick="openCollectionModal('{{ $user->id }}', '{{ addslashes($user->name) }}')">
                    @php
                        $favs = $user->favorites->take(3);
                    @endphp

                    @if($favs->count() >= 3)
                        <!-- Back Left -->
                        <div class="absolute top-0 left-0 w-full h-full rounded-xl overflow-hidden transform -rotate-6 -translate-x-4 scale-90 opacity-60 transition-transform group-hover:-translate-x-6 group-hover:-rotate-12 border border-white/10 bg-zinc-900">
                            <img src="{{ $favs[2]->poster_url }}" class="w-full h-full object-cover">
                        </div>
                        <!-- Back Right -->
                        <div class="absolute top-0 left-0 w-full h-full rounded-xl overflow-hidden transform rotate-6 translate-x-4 scale-90 opacity-60 transition-transform group-hover:translate-x-6 group-hover:rotate-12 border border-white/10 bg-zinc-900">
                            <img src="{{ $favs[1]->poster_url }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    
                    @if($favs->count() >= 1)
                        <!-- Front -->
                        <div class="absolute top-0 left-0 w-full h-full rounded-xl overflow-hidden shadow-2xl transform transition-transform group-hover:scale-105 border border-white/10 bg-zinc-900 z-10">
                            <img src="{{ $favs[0]->poster_url }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                        </div>
                    @else
                        <div class="absolute top-0 left-0 w-full h-full rounded-xl overflow-hidden shadow-2xl border border-white/10 bg-zinc-800 flex items-center justify-center z-10">
                            <span class="text-zinc-500 text-xs">Пусто</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $users->links() }}
    </div>
</div>

<!-- Collection Modal -->
<div id="collection-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeCollectionModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl h-[80vh] flex flex-col">
        <div class="glass-card rounded-2xl flex flex-col h-full overflow-hidden">
            <div class="p-6 border-b border-white/10 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white">Коллекция <span id="modal-username" class="text-blue-400"></span></h2>
                <button onclick="closeCollectionModal()" class="text-zinc-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-grow">
                <div id="modal-loader" class="flex justify-center items-center h-full">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                </div>
                <div id="modal-content" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 hidden">
                    <!-- Content injected via JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openCollectionModal(userId, userName) {
        const modal = document.getElementById('collection-modal');
        const usernameSpan = document.getElementById('modal-username');
        const loader = document.getElementById('modal-loader');
        const content = document.getElementById('modal-content');
        
        usernameSpan.textContent = userName;
        modal.classList.remove('hidden');
        loader.classList.remove('hidden');
        content.classList.add('hidden');
        content.innerHTML = '';

        fetch(`/collections/${userId}/favorites`)
            .then(response => response.json())
            .then(data => {
                loader.classList.add('hidden');
                content.classList.remove('hidden');
                
                if (data.length === 0) {
                    content.innerHTML = '<p class="text-zinc-500 col-span-full text-center">Коллекция пуста</p>';
                    return;
                }

                data.forEach(anime => {
                    const card = document.createElement('a');
                    card.href = `/anime/${anime.anime_id}`;
                    card.className = 'aspect-[3/4] rounded-xl overflow-hidden relative group cursor-pointer block';
                    card.innerHTML = `
                        <img src="${anime.poster_url || 'https://via.placeholder.com/150'}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                            <span class="text-white text-sm font-medium truncate w-full">${anime.title}</span>
                        </div>
                    `;
                    content.appendChild(card);
                });
            })
            .catch(err => {
                console.error(err);
                loader.classList.add('hidden');
                content.innerHTML = '<p class="text-red-500 col-span-full text-center">Ошибка загрузки</p>';
                content.classList.remove('hidden');
            });
    }

    function closeCollectionModal() {
        document.getElementById('collection-modal').classList.add('hidden');
    }
</script>
@endsection
