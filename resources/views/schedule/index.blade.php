@extends('layouts.app')

@section('title', 'Расписание выхода серий - Nekomi')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Расписание</h1>
            <p class="text-zinc-400 text-sm mt-1">График выхода новых серий</p>
        </div>
    </div>

    @if(empty($schedule))
        <div class="glass-card p-8 rounded-2xl text-center">
            <p class="text-zinc-500">Расписание временно недоступно.</p>
        </div>
    @else
        <!-- Tabs Navigation -->
        <div class="flex overflow-x-auto pb-4 gap-2 mb-6 no-scrollbar" id="schedule-tabs">
            @foreach($schedule as $index => $day)
                <button onclick="switchTab({{ $index }})" 
                        class="tab-btn px-6 py-3 rounded-xl font-medium transition-all whitespace-nowrap {{ $index === 0 ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'glass text-zinc-400 hover:text-white hover:bg-white/10' }}"
                        data-index="{{ $index }}">
                    {{ $day['day']['name'] }}
                </button>
            @endforeach
        </div>

        <!-- Tabs Content -->
        <div class="relative min-h-[500px]">
            @foreach($schedule as $index => $day)
                <div id="day-{{ $index }}" class="tab-content {{ $index === 0 ? 'block' : 'hidden' }} animate-fade-in">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($day['releases'] as $anime)
                            <a href="{{ route('anime.show', ['id' => $anime['id']]) }}" class="group relative glass-card rounded-xl overflow-hidden hover:border-blue-500/30 transition-all duration-300 hover:-translate-y-1 cursor-pointer block">
                                <div class="aspect-[3/4] relative overflow-hidden">
                                    <img src="https://cdn.aniliberty.top{{ $anime['poster']['optimized']['preview'] ?? $anime['poster']['preview'] ?? 'https://via.placeholder.com/300x450' }}" alt="Cover" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
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
                                            Новый эпизод: {{ $anime['next_episode'] ?? '?' }}
                                        </span>
                                        <span class="border border-zinc-800 rounded px-1.5 py-0.5 text-zinc-400">{{ $anime['type']['description'] ?? 'TV' }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function switchTab(index) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        // Show selected content
        const content = document.getElementById('day-' + index);
        if (content) {
            content.classList.remove('hidden');
            content.classList.add('block');
        }
        
        // Update buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            if (parseInt(btn.dataset.index) === index) {
                btn.classList.remove('glass', 'text-zinc-400', 'hover:text-white', 'hover:bg-white/10');
                btn.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/20');
            } else {
                btn.classList.add('glass', 'text-zinc-400', 'hover:text-white', 'hover:bg-white/10');
                btn.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/20');
            }
        });
    }
</script>

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
