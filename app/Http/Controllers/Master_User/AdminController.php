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

class AdminController extends Controller
{
    /**
     * Display a listing of admins
     */
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->with('departemen')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $departemens = Departemen::all();

        return view('master_user.admin', compact('admins', 'departemens'));
    }

    /**
     * Store a newly created admin
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
            $validated['role'] = 'admin';

            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('users', 'public');
            }

            User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil ditambahkan'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error create admin:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified admin
     */
    public function show($id)
    {
        try {
            $admin = User::where('role', 'admin')->with('departemen')->findOrFail($id);

            $response = [
                'id' => $admin->id,
                'nik' => $admin->nik ?? '',
                'username' => $admin->username ?? '',
                'nama' => $admin->nama ?? '',
                'email' => $admin->email ?? '',
                'no_telepon' => $admin->no_telepon ?? '',
                'departemen_id' => $admin->departemen_id ?? null,
                'photo' => $admin->photo ?? null,
                'photo_url' => $admin->photo ? asset('storage/' . $admin->photo) : null,
                'role' => $admin->role ?? '',
            ];

            Log::info('Show Admin ID ' . $id . ':', $response);

            return response()->json($response);

        } catch (\Throwable $e) {
            Log::error('Show Error ID ' . $id . ':', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Data tidak ditemukan (ID: ' . $id . ')'
            ], 404);
        }
    }

    /**
     * Update the specified admin
     */
    public function update(Request $request, $id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);

            $validated = $request->validate([
                'nik' => ['required', 'string', 'max:20', Rule::unique('users', 'nik')->ignore($admin->id)],
                'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($admin->id)],
                'nama' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($admin->id)],
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
                if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
                    Storage::disk('public')->delete($admin->photo);
                }
                $validated['photo'] = $request->file('photo')->store('users', 'public');
            }

            $admin->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil diperbarui'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error update admin:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified admin
     */
    public function destroy($id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);

            if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
                Storage::disk('public')->delete($admin->photo);
            }

            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error delete admin:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
