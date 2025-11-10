<?php

namespace App\Http\Controllers\Master_User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdministratorController extends Controller
{
    /**
     * Display a listing of administrators
     */
    public function index()
    {
        $administrators = User::where('role', 'administrator')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('master_user.administrator', compact('administrators'));
    }

    /**
     * Store a newly created administrator
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
                'password' => 'required|string|min:6|confirmed',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            $validated['role'] = 'administrator';
            $validated['departemen_id'] = null;  // Administrator tidak perlu departemen

            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('users', 'public');
            }

            User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Administrator berhasil ditambahkan'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error create administrator:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified administrator
     */
    public function show($id)
    {
        try {
            $administrator = User::where('role', 'administrator')->findOrFail($id);

            $response = [
                'id' => $administrator->id,
                'nik' => $administrator->nik ?? '',
                'username' => $administrator->username ?? '',
                'nama' => $administrator->nama ?? '',
                'email' => $administrator->email ?? '',
                'no_telepon' => $administrator->no_telepon ?? '',
                'photo' => $administrator->photo ?? null,
                'photo_url' => $administrator->photo ? asset('storage/' . $administrator->photo) : null,
                'role' => $administrator->role ?? '',
            ];

            Log::info('Show Administrator ID ' . $id . ':', $response);

            return response()->json($response);

        } catch (\Throwable $e) {
            Log::error('Show Error ID ' . $id . ':', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Data tidak ditemukan (ID: ' . $id . ')'
            ], 404);
        }
    }

    /**
     * Update the specified administrator
     */
    public function update(Request $request, $id)
    {
        try {
            $administrator = User::where('role', 'administrator')->findOrFail($id);

            $validated = $request->validate([
                'nik' => ['required', 'string', 'max:20', Rule::unique('users', 'nik')->ignore($administrator->id)],
                'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($administrator->id)],
                'nama' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($administrator->id)],
                'no_telepon' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6|confirmed',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            if ($request->hasFile('photo')) {
                if ($administrator->photo && Storage::disk('public')->exists($administrator->photo)) {
                    Storage::disk('public')->delete($administrator->photo);
                }
                $validated['photo'] = $request->file('photo')->store('users', 'public');
            }

            $administrator->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Administrator berhasil diperbarui'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error update administrator:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified administrator
     */
    public function destroy($id)
    {
        try {
            $administrator = User::where('role', 'administrator')->findOrFail($id);

            if ($administrator->photo && Storage::disk('public')->exists($administrator->photo)) {
                Storage::disk('public')->delete($administrator->photo);
            }

            $administrator->delete();

            return response()->json([
                'success' => true,
                'message' => 'Administrator berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error delete administrator:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
