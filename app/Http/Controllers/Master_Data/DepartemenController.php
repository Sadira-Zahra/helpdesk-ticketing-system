<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DepartemenController extends Controller
{
    /**
     * Display a listing of departemen
     */
    public function index()
    {
        $departemens = Departemen::orderBy('id', 'desc')->paginate(10);

        return view('master_data.departemen', compact('departemens'));
    }

    /**
     * Store a newly created departemen
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_departemen' => 'required|string|unique:departemen,nama_departemen|max:255',
            ]);

            Departemen::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil ditambahkan'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error create departemen:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified departemen
     */
    public function show($id)
    {
        try {
            $departemen = Departemen::findOrFail($id);

            $response = [
                'id' => $departemen->id,
                'nama_departemen' => $departemen->nama_departemen ?? '',
            ];

            Log::info('Show Departemen ID ' . $id . ':', $response);

            return response()->json($response);

        } catch (\Throwable $e) {
            Log::error('Show Error ID ' . $id . ':', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Data tidak ditemukan (ID: ' . $id . ')'
            ], 404);
        }
    }

    /**
     * Update the specified departemen
     */
    public function update(Request $request, $id)
    {
        try {
            $departemen = Departemen::findOrFail($id);

            $validated = $request->validate([
                'nama_departemen' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('departemen', 'nama_departemen')->ignore($departemen->id)
                ],
            ]);

            $departemen->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil diperbarui'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error update departemen:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified departemen
     */
    public function destroy($id)
    {
        try {
            $departemen = Departemen::findOrFail($id);

            $departemen->delete();

            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error delete departemen:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
