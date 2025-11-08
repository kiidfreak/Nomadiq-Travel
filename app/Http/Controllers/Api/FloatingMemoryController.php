<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\FloatingMemory;
use Illuminate\Http\Request;

class FloatingMemoryController extends Controller
{
    /**
     * Display a listing of published memories.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get only published memories, ordered by slot first, then by latest
        $memories = FloatingMemory::where('is_published', true)
            ->orderByRaw('slot IS NULL, slot ASC')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $memories,
        ]);
    }

    /**
     * Display the latest published memories.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest()
    {
        // Get latest published memories with their destinations and packages
        $latestMemories = FloatingMemory::where('is_published', true)
            ->with(['destination', 'package'])
            ->orderByRaw('slot IS NULL, slot ASC')
            ->latest()
            ->take(8)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $latestMemories,
        ]);
    }

    /**
     * Get memories by specific slots for website positioning.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bySlots(Request $request)
    {
        $slots = $request->input('slots', [1, 2, 3, 4, 5]);
        
        $memories = FloatingMemory::where('is_published', true)
            ->whereIn('slot', $slots)
            ->with(['destination', 'package'])
            ->orderBy('slot')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $memories,
        ]);
    }

    /**
     * Display a single memory.
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $memory = FloatingMemory::where('is_published', true)
            ->with(['destination', 'package'])
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $memory,
        ]);
    }
}
