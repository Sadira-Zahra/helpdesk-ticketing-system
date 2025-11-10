<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use App\Models\Urgency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UrgencyController extends Controller
{
    /**
     * Display a listing of urgency
     */
    public function index()
    {
        $urgencies = Urgency::orderBy('jam', 'asc')->paginate(10);

        return view('master_data.urgency', compact('urgencies'));
    }

    /**
     * Store a newly created urgency
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'urgency' => 'required|string|unique:urgency,urgency|max:255',
                'jam' => 'required|integer|min:1|max:9999|unique:urgency,jam',
            ]);

            Urgency::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Urgency berhasil ditambahkan'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error create urgency:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified urgency
     */
    public function show($id)
    {
        try {
            $urgency = Urgency::findOrFail($id);

            $response = [
                'id' => $urgency->id,
                'urgency' => $urgency->urgency ?? '',
                'jam' => $urgency->jam ?? 0,
            ];

            Log::info('Show Urgency ID ' . $id . ':', $response);

            return response()->json($response);

        } catch (\Throwable $e) {
            Log::error('Show Error ID ' . $id . ':', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Data tidak ditemukan (ID: ' . $id . ')'
            ], 404);
        }
    }

    /**
     * Update the specified urgency
     */
    public function update(Request $request, $id)
    {
        try {
            $urgency = Urgency::findOrFail($id);

            $validated = $request->validate([
                'urgency' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('urgency', 'urgency')->ignore($urgency->id)
                ],
                'jam' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:9999',
                    Rule::unique('urgency', 'jam')->ignore($urgency->id)
                ],
            ]);

            $urgency->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Urgency berhasil diperbarui'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error update urgency:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified urgency
     */
    public function destroy($id)
    {
        try {
            $urgency = Urgency::findOrFail($id);

            $urgency->delete();

            return response()->json([
                'success' => true,
                'message' => 'Urgency berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error delete urgency:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
