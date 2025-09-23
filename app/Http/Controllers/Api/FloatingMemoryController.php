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
        // Get only published memories
        $memories = FloatingMemory::where('is_published', true)
            ->with('destination')
            ->latest()
            ->paginate(12);
        
        return response()->json([
            'success' => true,
            'data' => $memories,
        ]);
    }

    /**
     * Display the latest memories.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest()
    {
        // Get only published memories
        $latestMemories = FloatingMemory::where('is_published', true)
            ->with('destination')
            ->latest()
            ->take(8)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $latestMemories,
        ]);
    }
}
