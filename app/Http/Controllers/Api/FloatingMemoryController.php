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
        // Get only published memories, just memory fields
        $memories = FloatingMemory::where('is_published', true)
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
            ->latest()
            ->take(8)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $latestMemories,
        ]);
    }
}
