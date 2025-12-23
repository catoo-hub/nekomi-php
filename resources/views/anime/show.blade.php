@extends('layouts.app')

@section('title', $anime['name']['main'] . ' - Nekomi')

@section('content')
<div class="relative">
    <!-- Background Image with Blur -->
    <div class="absolute inset-0 h-[50vh] z-0">
        <img src="https://cdn.aniliberty.top{{ $anime['poster']['optimized']['preview'] ?? $anime['poster']['preview'] ?? 'https://via.placeholder.com/1920x1080' }}" alt="Background" class="w-full h-full object-cover opacity-30 blur-[32px] scale-165 mask-image-gradient">
        <!-- <div class="absolute inset-0 bg-gradient-to-b from-zinc-950/0 via-zinc-950/80 to-zinc-950"></div> -->
    </div>

    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Player & Episodes -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Player Container -->
            <div class="glass-card rounded-2xl aspect-video relative group overflow-hidden rounded-2xl" >
                @if(isset($currentEpisode))
                    <div id="player" class="w-full h-full"></div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var player = new Playerjs({
                                id: "player",
                                file: "[480p]{{ $currentEpisode['hls_480'] }},[720p]{{ $currentEpisode['hls_720'] }},[1080p]{{ $currentEpisode['hls_1080'] }}",
                                poster: "https://cdn.aniliberty.top{{ $currentEpisode['preview']['optimized']['thumbnail'] ?? $currentEpisode['preview']['thumbnail'] ?? '' }}",
                                start: {{ $userProgress->time_watched ?? 0 }}
                            });

                            let lastSaveTime = 0;

                            function saveProgress(time, duration) {
                                // Save every 10 seconds
                                if (Math.abs(time - lastSaveTime) < 10) return;
                                lastSaveTime = time;

                                fetch('{{ route('anime.progress') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        anime_id: '{{ $anime['id'] }}',
                                        episode_id: '{{ $currentEpisode['id'] }}',
                                        episode_number: '{{ $currentEpisode['ordinal'] ?? 0 }}',
                                        time_watched: time,
                                        duration: duration
                                    })
                                }).catch(console.error);
                            }

                            // Check progress every 5 seconds (Fallback for PlayerJS events)
                            setInterval(function() {
                                if (player && player.api) {
                                    try {
                                        const currentTime = player.api("time");
                                        const duration = player.api("duration");
                                        
                                        if (typeof currentTime === 'number' && currentTime > 0) {
                                            saveProgress(currentTime, duration);
                                        }
                                    } catch (e) {
                                        // Player might not be ready
                                    }
                                }
                            }, 5000);
                        });
                    </script>
                @else
                    <div class="absolute inset-0 flex items-center justify-center bg-black/50">
                        <p class="text-zinc-500">Плеер не доступен</p>
                    </div>
                @endif
            </div>

            <!-- Episode List -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Эпизоды
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if(isset($anime['episodes']) && count($anime['episodes']) > 0)
                        @foreach($anime['episodes'] as $episode)
                            @php
                                $isWatched = in_array($episode['id'], $watchedEpisodes ?? []);
                                $isActive = isset($currentEpisode) && $currentEpisode['id'] == $episode['id'];
                            @endphp
                            <a href="{{ route('anime.episode', ['id' => $anime['id'], 'episodeId' => $episode['id']]) }}" class="glass-card p-3 rounded-xl flex gap-4 hover:bg-white/5 transition-colors cursor-pointer group {{ $isActive ? 'border-blue-500/50 bg-blue-500/10' : ($isWatched ? 'border-green-500/30 bg-green-500/5' : '') }}">
                                <div class="w-24 h-16 rounded-lg overflow-hidden relative flex-shrink-0">
                                    <img src="https://cdn.aniliberty.top{{ $episode['preview']['optimized']['thumbnail'] ?? $episode['preview']['thumbnail'] ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" /></svg>
                                    </div>
                                    @if($isWatched)
                                        <div class="absolute bottom-1 right-1 bg-green-500 text-white text-[10px] px-1 rounded shadow">Просмотрено</div>
                                    @endif
                                </div>
                                <div class="flex flex-col justify-center">
                                    <span class="{{ $isActive ? 'text-blue-400' : ($isWatched ? 'text-green-400' : 'text-zinc-500') }} text-xs font-bold mb-1">Эпизод {{ $episode['ordinal'] }}</span>
                                    <h4 class="text-zinc-200 text-sm font-medium line-clamp-1 group-hover:text-white transition-colors">{{ $episode['name'] ?? 'Episode ' . $episode['ordinal'] }}</h4>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <p class="text-zinc-500">Эпизоды не найдены.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Details -->
        <div class="space-y-6">
            <div class="glass-card p-6 rounded-2xl">
                <h1 class="text-3xl font-bold text-white mb-2">{{ $anime['name']['main'] }}</h1>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-2 py-1 rounded bg-green-500/20 text-green-400 text-xs font-bold border border-green-500/20">{{ $anime['added_in_users_favorites'] ?? 0 }} избранных</span>
                    <span class="px-2 py-1 rounded bg-zinc-800 text-zinc-400 text-xs border border-zinc-700">{{ $anime['type']['description'] ?? 'TV' }}</span>
                    <span class="px-2 py-1 rounded bg-zinc-800 text-zinc-400 text-xs border border-zinc-700">{{ $anime['year'] ?? 'N/A' }}</span>
                </div>
                
                <p class="text-zinc-400 text-sm leading-relaxed mb-6">
                    {{ $anime['description'] ?? 'Описание недоступно.' }}
                </p>

                <div class="space-y-3">
                    <button id="favorite-btn" onclick="toggleFavorite('{{ $anime['id'] }}', '{{ addslashes($anime['name']['main']) }}', 'https://cdn.aniliberty.top{{ $anime['poster']['optimized']['preview'] ?? $anime['poster']['preview'] ?? '' }}')" 
                        class="w-full shadow-lg {{ $isFavorite ? 'bg-red-600 hover:bg-red-500 shadow-red-500/20' : 'bg-blue-600 hover:bg-blue-500 shadow-blue-500/20' }} text-white py-3 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <span id="favorite-text">{{ $isFavorite ? 'Убрать из коллекции' : 'Добавить в коллекцию' }}</span>
                    </button>
                    <button onclick="shareAnime()" class="w-full glass hover:bg-white/10 text-zinc-300 hover:text-white py-3 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        Поделиться
                    </button>
                </div>
            </div>

            <!-- Aniliberty Block -->
            <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-blue-500" fill="white" viewBox="0 0 1174 1174"><path d="M698 245c0-7 8-12 14-9l169 77c4 2 6 6 6 9v395c0 6 4 10 10 10h248c6 0 11 6 9 12-25.3 94.2-73.6 180.7-140.7 251.5-67.1 70.9-150.8 123.9-243.5 154.2-92.7 30.4-191.5 37.2-287.5 19.8-96-17.4-186.2-58.5-262.3-119.5-3-3-4-8-2-12l34-58c3-5 10-7 15-3 51.5 43.1 111.1 75.4 175.4 94.9 64.2 19.6 131.7 25.9 198.5 18.7 66.7-7.2 131.4-27.8 190-60.5 58.6-32.8 110-77 151.1-130.1 5-7 1-16-8-16h-93c-4 0-7-2-9-5L699 574l-1-5z"></path><path d="M422 240c4-7 14-7 18 0l360 624c4 7-1 15-8 15H628c-4 0-7-2-9-5l-43-75c-2-3-5-5-9-5H373c-7 0-12-8-8-15l65-113c1-3 5-5 8-5h41c7 0 12-8 8-15l-47-83c-4-7-14-7-18 0L211 929v1l-23 39-1 1-10 19c-4 6-12 7-16 2-36-38-67-82-92-128-2-3-2-7 0-10l10-19 2-2v-1z"></path><path d="M587 0c83.2.1 165.5 17.8 241.3 52.1 75.8 34.3 143.5 84.2 198.5 146.7 55.1 62.4 96.2 135.8 120.7 215.3s31.8 163.3 21.5 245.9c0 5-4 9-9 9h-67c-6 0-11-6-10-12 20.8-146.3-24-294.3-122.5-404.5C862.1 142.4 720 81.3 572.3 85.6 424.6 90 286.3 159.3 194.5 275 102.7 390.8 66.6 541.2 96 686l-1 7-47 81c-5 7-15 6-18-2C.5 683.8-7.6 589.8 6.4 497.8c14-91.9 49.6-179.2 104.1-254.7 54.4-75.4 126-136.8 208.9-179.1C402.2 21.7 494-.2 587 0z"></path></svg>
                </div>
                
                <h3 class="text-white font-bold mb-4 relative z-10">При поддержке</h3>
                
                <div class="flex items-center gap-4 mb-4 relative z-10">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white-500" fill="white" viewBox="0 0 1174 1174"><path d="M698 245c0-7 8-12 14-9l169 77c4 2 6 6 6 9v395c0 6 4 10 10 10h248c6 0 11 6 9 12-25.3 94.2-73.6 180.7-140.7 251.5-67.1 70.9-150.8 123.9-243.5 154.2-92.7 30.4-191.5 37.2-287.5 19.8-96-17.4-186.2-58.5-262.3-119.5-3-3-4-8-2-12l34-58c3-5 10-7 15-3 51.5 43.1 111.1 75.4 175.4 94.9 64.2 19.6 131.7 25.9 198.5 18.7 66.7-7.2 131.4-27.8 190-60.5 58.6-32.8 110-77 151.1-130.1 5-7 1-16-8-16h-93c-4 0-7-2-9-5L699 574l-1-5z"></path><path d="M422 240c4-7 14-7 18 0l360 624c4 7-1 15-8 15H628c-4 0-7-2-9-5l-43-75c-2-3-5-5-9-5H373c-7 0-12-8-8-15l65-113c1-3 5-5 8-5h41c7 0 12-8 8-15l-47-83c-4-7-14-7-18 0L211 929v1l-23 39-1 1-10 19c-4 6-12 7-16 2-36-38-67-82-92-128-2-3-2-7 0-10l10-19 2-2v-1z"></path><path d="M587 0c83.2.1 165.5 17.8 241.3 52.1 75.8 34.3 143.5 84.2 198.5 146.7 55.1 62.4 96.2 135.8 120.7 215.3s31.8 163.3 21.5 245.9c0 5-4 9-9 9h-67c-6 0-11-6-10-12 20.8-146.3-24-294.3-122.5-404.5C862.1 142.4 720 81.3 572.3 85.6 424.6 90 286.3 159.3 194.5 275 102.7 390.8 66.6 541.2 96 686l-1 7-47 81c-5 7-15 6-18-2C.5 683.8-7.6 589.8 6.4 497.8c14-91.9 49.6-179.2 104.1-254.7 54.4-75.4 126-136.8 208.9-179.1C402.2 21.7 494-.2 587 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-lg">AniLiberty</h4>
                        <p class="text-zinc-400 text-xs">Лучшая любительская озвучка аниме</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 relative z-10">
                    <a href="https://aniliberty.top" target="_blank" class="flex items-center justify-center gap-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white py-2 rounded-lg text-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        Сайт
                    </a>
                    <a href="https://t.me/aniliberty_tv" target="_blank" class="flex items-center justify-center gap-2 bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 hover:text-blue-300 py-2 rounded-lg text-sm transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                        Telegram
                    </a>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-white font-bold mb-4">Информация</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Статус</span>
                        <span class="text-zinc-300">{{ isset($anime['is_ongoing']) && $anime['is_ongoing'] ? 'В процессе' : 'Завершено' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Длительность</span>
                        <span class="text-zinc-300">{{ $anime['average_duration_of_episode'] ?? '?' }} мин/эп</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Жанры</span>
                        <span class="text-blue-400">
                            @if(isset($anime['genres']))
                                {{ implode(', ', array_column($anime['genres'], 'name')) }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleFavorite(animeId, title, posterUrl) {
        fetch('{{ route('favorites.toggle') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                anime_id: animeId,
                title: title,
                poster_url: posterUrl
            })
        })
        .then(response => {
            if (response.status === 401) {
                window.location.href = '{{ route('login') }}';
                return;
            }
            return response.json();
        })
        .then(data => {
            const btn = document.getElementById('favorite-btn');
            const text = document.getElementById('favorite-text');
            const icon = btn.querySelector('svg');
            
            if (data.status === 'added') {
                btn.classList.remove('bg-blue-600', 'hover:bg-blue-500', 'shadow-blue-500/20');
                btn.classList.add('bg-red-600', 'hover:bg-red-500', 'shadow-red-500/20');
                text.textContent = 'Убрать из коллекции';
                icon.setAttribute('fill', 'currentColor');
            } else {
                btn.classList.remove('bg-red-600', 'hover:bg-red-500', 'shadow-red-500/20');
                btn.classList.add('bg-blue-600', 'hover:bg-blue-500', 'shadow-blue-500/20');
                text.textContent = 'Добавить в коллекцию';
                icon.setAttribute('fill', 'none');
            }
        });
    }

    function shareAnime() {
        const url = window.location.href;
        const title = '{{ $anime['name']['main'] }}';
        const text = 'Зацени это аниме на Nekomi!';

        // Использование Web Share API
        if (navigator.share) {
            navigator.share({
                title: title,
                text: text,
                url: url,
            }).catch(console.error);
            return;
        }

        // Сначала пытаемся использовать Clipboard API
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url)
                .then(() => alert('Ссылка скопирована в буфер обмена!'))
                .catch(err => {
                    console.error('Clipboard API failed:', err);
                    fallbackCopy(url);
                });
        } else {
            fallbackCopy(url);
        }
    }

    // Фоллбек метод для копирования текста
    function fallbackCopy(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                alert('Ссылка скопирована в буфер обмена!');
            } else {
                alert('Не удалось скопировать ссылку.');
            }
        } catch (err) {
            console.error('Fallback copy failed:', err);
            alert('Не удалось скопировать ссылку.');
        }
        
        document.body.removeChild(textArea);
    }
</script>
@endpush

