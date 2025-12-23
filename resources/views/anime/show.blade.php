@extends('layouts.app')

@section('title', $anime['name']['main'] . ' - Nekomi')

@section('content')
<div class="relative">
    <!-- Background Image with Blur -->
    <div class="absolute inset-0 h-[50vh] z-0">
        <img src="https://cdn.aniliberty.top{{ $anime['poster']['optimized']['preview'] ?? $anime['poster']['preview'] ?? 'https://via.placeholder.com/1920x1080' }}" alt="Background" class="w-full h-full object-cover opacity-40 blur-3xl mask-image-gradient">
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
                @elseif(isset($anime['external_player']))
                    <iframe src="{{ $anime['external_player'] }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                @else
                    <div class="absolute inset-0 flex items-center justify-center bg-black/50">
                        <p class="text-zinc-500">Player not available</p>
                    </div>
                @endif
            </div>

            <!-- Episode List -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Episodes
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
                                        <div class="absolute bottom-1 right-1 bg-green-500 text-white text-[10px] px-1 rounded shadow">Watched</div>
                                    @endif
                                </div>
                                <div class="flex flex-col justify-center">
                                    <span class="{{ $isActive ? 'text-blue-400' : ($isWatched ? 'text-green-400' : 'text-zinc-500') }} text-xs font-bold mb-1">EP {{ $episode['ordinal'] }}</span>
                                    <h4 class="text-zinc-200 text-sm font-medium line-clamp-1 group-hover:text-white transition-colors">{{ $episode['name'] ?? 'Episode ' . $episode['ordinal'] }}</h4>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <p class="text-zinc-500">No episodes found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Details -->
        <div class="space-y-6">
            <div class="glass-card p-6 rounded-2xl">
                <h1 class="text-3xl font-bold text-white mb-2">{{ $anime['name']['main'] }}</h1>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-2 py-1 rounded bg-green-500/20 text-green-400 text-xs font-bold border border-green-500/20">{{ $anime['added_in_users_favorites'] ?? 0 }} Favorites</span>
                    <span class="px-2 py-1 rounded bg-zinc-800 text-zinc-400 text-xs border border-zinc-700">{{ $anime['type']['description'] ?? 'TV' }}</span>
                    <span class="px-2 py-1 rounded bg-zinc-800 text-zinc-400 text-xs border border-zinc-700">{{ $anime['year'] ?? 'N/A' }}</span>
                </div>
                
                <p class="text-zinc-400 text-sm leading-relaxed mb-6">
                    {{ $anime['description'] ?? 'No description available.' }}
                </p>

                <div class="space-y-3">
                    <button id="favorite-btn" onclick="toggleFavorite('{{ $anime['id'] }}', '{{ addslashes($anime['name']['main']) }}', 'https://cdn.aniliberty.top{{ $anime['poster']['optimized']['preview'] ?? $anime['poster']['preview'] ?? '' }}')" 
                        class="w-full {{ $isFavorite ? 'bg-red-600 hover:bg-red-500' : 'bg-blue-600 hover:bg-blue-500' }} text-white py-3 rounded-xl font-semibold shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <span id="favorite-text">{{ $isFavorite ? 'Remove from Collection' : 'Add to Collection' }}</span>
                    </button>
                    <button onclick="shareAnime()" class="w-full glass hover:bg-white/10 text-zinc-300 hover:text-white py-3 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        Share
                    </button>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-white font-bold mb-4">Information</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Status</span>
                        <span class="text-zinc-300">{{ isset($anime['is_ongoing']) && $anime['is_ongoing'] ? 'Ongoing' : 'Finished' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Duration</span>
                        <span class="text-zinc-300">{{ $anime['average_duration_of_episode'] ?? '?' }} min/ep</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Genres</span>
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
                btn.classList.remove('bg-blue-600', 'hover:bg-blue-500');
                btn.classList.add('bg-red-600', 'hover:bg-red-500');
                text.textContent = 'Remove from Collection';
                icon.setAttribute('fill', 'currentColor');
            } else {
                btn.classList.remove('bg-red-600', 'hover:bg-red-500');
                btn.classList.add('bg-blue-600', 'hover:bg-blue-500');
                text.textContent = 'Add to Collection';
                icon.setAttribute('fill', 'none');
            }
        });
    }

    function shareAnime() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $anime['name']['main'] }}',
                text: 'Check out this anime on Nekomi!',
                url: window.location.href,
            });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Link copied to clipboard!');
        }
    }
</script>
@endpush

