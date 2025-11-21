<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan info profil user
     */
    public function info()
    {
        $user = Auth::user();
        return view('profile-info', compact('user'));
    }

    /**
     * Tampilkan form edit profil
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profil user
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // Reset verifikasi email jika email diganti
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun user
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Tampilkan form ganti password
     */
    public function editPassword(Request $request): View
    {
        return view('profile.partials.update-password-form', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update password user
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = $request->user();

        // Update password baru
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return Redirect::route('password.edit')->with('status', 'password-updated');
    }
}
