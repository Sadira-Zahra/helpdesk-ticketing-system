<?php

namespace App\Http\Controllers\Master_User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->with('departemen')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $departemens = Departemen::all();

        return view('master_user.user', compact('users', 'departemens'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nik' => 'required|string|unique:users,nik|max:20',
                'username' => 'required|string|unique:users,username|max:50',
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'no_telepon' => 'nullable|string|max:20',
                'departemen_id' => 'required|exists:departemen,id',
                'password' => 'required|string|min:6|confirmed',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            $validated['role'] = 'user';

            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('users', 'public');
            }

            User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error create user:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        try {
            $user = User::where('role', 'user')->with('departemen')->findOrFail($id);

            $response = [
                'id' => $user->id,
                'nik' => $user->nik ?? '',
                'username' => $user->username ?? '',
                'nama' => $user->nama ?? '',
                'email' => $user->email ?? '',
                'no_telepon' => $user->no_telepon ?? '',
                'departemen_id' => $user->departemen_id ?? null,
                'photo' => $user->photo ?? null,
                'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
                'role' => $user->role ?? '',
            ];

            Log::info('Show User ID ' . $id . ':', $response);

            return response()->json($response);

        } catch (\Throwable $e) {
            Log::error('Show Error ID ' . $id . ':', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Data tidak ditemukan (ID: ' . $id . ')'
            ], 404);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::where('role', 'user')->findOrFail($id);

            $validated = $request->validate([
                'nik' => ['required', 'string', 'max:20', Rule::unique('users', 'nik')->ignore($user->id)],
                'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
                'nama' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
                'no_telepon' => 'nullable|string|max:20',
                'departemen_id' => 'required|exists:departemen,id',
                'password' => 'nullable|string|min:6|confirmed',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            if ($request->hasFile('photo')) {
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                $validated['photo'] = $request->file('photo')->store('users', 'public');
            }

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error update user:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified user
     */
    public function destroy($id)
    {
        try {
            $user = User::where('role', 'user')->findOrFail($id);

            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error delete user:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
