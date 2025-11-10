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

class TeknisiController extends Controller
{
    /**
     * Display a listing of teknisi
     */
    public function index()
    {
        $teknisis = User::where('role', 'teknisi')
            ->with('departemen')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $departemens = Departemen::all();

        return view('master_user.teknisi', compact('teknisis', 'departemens'));
    }

    /**
     * Store a newly created teknisi
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
            $validated['role'] = 'teknisi';

            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('users', 'public');
            }

            User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Teknisi berhasil ditambahkan'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error create teknisi:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified teknisi
     */
    public function show($id)
    {
        try {
            $teknisi = User::where('role', 'teknisi')->with('departemen')->findOrFail($id);

            $response = [
                'id' => $teknisi->id,
                'nik' => $teknisi->nik ?? '',
                'username' => $teknisi->username ?? '',
                'nama' => $teknisi->nama ?? '',
                'email' => $teknisi->email ?? '',
                'no_telepon' => $teknisi->no_telepon ?? '',
                'departemen_id' => $teknisi->departemen_id ?? null,
                'photo' => $teknisi->photo ?? null,
                'photo_url' => $teknisi->photo ? asset('storage/' . $teknisi->photo) : null,
                'role' => $teknisi->role ?? '',
            ];

            Log::info('Show Teknisi ID ' . $id . ':', $response);

            return response()->json($response);

        } catch (\Throwable $e) {
            Log::error('Show Error ID ' . $id . ':', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Data tidak ditemukan (ID: ' . $id . ')'
            ], 404);
        }
    }

    /**
     * Update the specified teknisi
     */
    public function update(Request $request, $id)
    {
        try {
            $teknisi = User::where('role', 'teknisi')->findOrFail($id);

            $validated = $request->validate([
                'nik' => ['required', 'string', 'max:20', Rule::unique('users', 'nik')->ignore($teknisi->id)],
                'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($teknisi->id)],
                'nama' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($teknisi->id)],
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
                if ($teknisi->photo && Storage::disk('public')->exists($teknisi->photo)) {
                    Storage::disk('public')->delete($teknisi->photo);
                }
                $validated['photo'] = $request->file('photo')->store('users', 'public');
            }

            $teknisi->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Teknisi berhasil diperbarui'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error update teknisi:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified teknisi
     */
    public function destroy($id)
    {
        try {
            $teknisi = User::where('role', 'teknisi')->findOrFail($id);

            if ($teknisi->photo && Storage::disk('public')->exists($teknisi->photo)) {
                Storage::disk('public')->delete($teknisi->photo);
            }

            $teknisi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Teknisi berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error delete teknisi:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
