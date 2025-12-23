@extends('layouts.app')

@section('title', 'Login - Nekomi')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="glass-card p-8 rounded-2xl w-full max-w-md relative overflow-hidden">
        <!-- Decorative Blob -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/20 blur-3xl rounded-full -mr-16 -mt-16 pointer-events-none"></div>

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">Welcome Back</h1>
            <p class="text-zinc-400 text-sm">Enter your credentials to access your account</p>
        </div>

        <form action="{{ url('/login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-zinc-400 mb-1.5 uppercase tracking-wider">Email</label>
                <input type="email" name="email" class="w-full bg-zinc-900/50 border border-zinc-800 rounded-lg px-4 py-3 text-zinc-200 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all placeholder-zinc-600" placeholder="name@example.com">
            </div>
            
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label class="block text-xs font-medium text-zinc-400 uppercase tracking-wider">Password</label>
                    <a href="#" class="text-xs text-blue-400 hover:text-blue-300">Forgot?</a>
                </div>
                <input type="password" name="password" class="w-full bg-zinc-900/50 border border-zinc-800 rounded-lg px-4 py-3 text-zinc-200 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all placeholder-zinc-600" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white font-bold py-3 rounded-lg shadow-lg shadow-blue-500/20 transition-all transform hover:scale-[1.02]">
                Sign In
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-white/5 text-center">
            <p class="text-zinc-500 text-sm">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">Sign up</a>
            </p>
        </div>
    </div>
</div>
@endsection
