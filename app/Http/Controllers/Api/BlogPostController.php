<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;

class BlogPostController extends Controller
{

    /**
     * Display a listing of blog posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
    $posts = BlogPost::published()->get();
        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified blog post.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $post = BlogPost::find($id);
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found.'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
}
