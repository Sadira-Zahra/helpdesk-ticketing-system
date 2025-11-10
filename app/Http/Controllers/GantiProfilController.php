<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Departemen;

class GantiProfilController extends Controller
{
    /**
     * Show ganti profil page
     */
    public function index()
    {
        $user = Auth::user();
        $departemens = Departemen::all();

        return view('profil.ganti_profil', compact('user', 'departemens'));
    }

    /**
     * Update profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        try {
            // Validasi dasar
            $rules = [
                'nik' => ['required', 'string', 'max:20', Rule::unique('users', 'nik')->ignore($user->id)],
                'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
                'nama' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
                'no_telepon' => 'nullable|string|max:20',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];

            // Tambahkan validasi departemen jika bukan administrator
            if ($user->role !== 'administrator') {
                $rules['departemen_id'] = 'required|exists:departemen,id';
            }

            $validated = $request->validate($rules);

            // Jika administrator, set departemen_id ke null
            if ($user->role === 'administrator') {
                $validated['departemen_id'] = null;
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                $validated['photo'] = $request->file('photo')->store('users', 'public');
            }

            // Update user
            $user->update($validated);

            return redirect()->route('ganti_profil.index')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ], [
                'current_password.required' => 'Password lama harus diisi',
                'new_password.required' => 'Password baru harus diisi',
                'new_password.min' => 'Password baru minimal 6 karakter',
                'new_password.confirmed' => 'Konfirmasi password tidak cocok',
            ]);

            // Check current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()
                    ->with('error', 'Password lama tidak sesuai!')
                    ->withInput();
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['new_password'])
            ]);

            return redirect()->route('ganti_profil.index')
                ->with('success', 'Password berhasil diubah!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
