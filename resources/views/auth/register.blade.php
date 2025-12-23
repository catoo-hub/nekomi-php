@extends('layouts.app')

@section('title', 'Register - Nekomi')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="glass-card p-8 rounded-2xl w-full max-w-md relative overflow-hidden">
        <!-- Decorative Blob -->
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-cyan-500/20 blur-3xl rounded-full -ml-16 -mb-16 pointer-events-none"></div>

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">Create Account</h1>
            <p class="text-zinc-400 text-sm">Join the Web3 anime community</p>
        </div>

        <form action="{{ url('/register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-zinc-400 mb-1.5 uppercase tracking-wider">Username</label>
                <input type="text" name="name" class="w-full bg-zinc-900/50 border border-zinc-800 rounded-lg px-4 py-3 text-zinc-200 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all placeholder-zinc-600" placeholder="NekomiUser">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-400 mb-1.5 uppercase tracking-wider">Email</label>
                <input type="email" name="email" class="w-full bg-zinc-900/50 border border-zinc-800 rounded-lg px-4 py-3 text-zinc-200 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all placeholder-zinc-600" placeholder="name@example.com">
            </div>
            
            <div>
                <label class="block text-xs font-medium text-zinc-400 mb-1.5 uppercase tracking-wider">Password</label>
                <input type="password" name="password" class="w-full bg-zinc-900/50 border border-zinc-800 rounded-lg px-4 py-3 text-zinc-200 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all placeholder-zinc-600" placeholder="••••••••">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-400 mb-1.5 uppercase tracking-wider">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full bg-zinc-900/50 border border-zinc-800 rounded-lg px-4 py-3 text-zinc-200 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all placeholder-zinc-600" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white font-bold py-3 rounded-lg shadow-lg shadow-blue-500/20 transition-all transform hover:scale-[1.02]">
                Create Account
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-white/5 text-center">
            <p class="text-zinc-500 text-sm">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
