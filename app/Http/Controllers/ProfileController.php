<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->latest()->get();
        return view('profile.show', compact('user', 'favorites'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:2048'], // 2MB Max
            'banner' => ['nullable', 'image', 'max:4096'], // 4MB Max
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $request->name;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->hasFile('banner')) {
            if ($user->banner) {
                Storage::disk('public')->delete($user->banner);
            }
            $path = $request->file('banner')->store('banners', 'public');
            $user->banner = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->is_collection_public = $request->has('is_collection_public');

        $user->save();

        return back()->with('status', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        if ($user->banner) {
            Storage::disk('public')->delete($user->banner);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Account deleted successfully.');
    }
}
