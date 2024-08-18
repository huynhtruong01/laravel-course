<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $page = (int)$request->query('page', 1);
        $perPage = (int)$request->query('limit', 2);
        $posts = Post::query()->paginate($perPage, ['*'], 'page', $page);

        $total = $posts->total();
        $data = $posts->items();
        $currentPage = $posts->currentPage();
        $lastPage = $posts->lastPage();
        $nextPage = $currentPage >= $lastPage ? null : $currentPage + 1;
        return response()->json([
            'data' => $data,
            'currentPage' => $currentPage,
            'nextPage' => $nextPage,
            'total' => $total,
        ]);
    }

    public function show(string $id)
    {
        $post = Post::find($id);
        if (empty($post)) {
            return response()->json([
                'status' => 404,
                'error' => 'Not found this post'
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'data' => $post
        ], 200);
    }

    public function create(Request $request)
    {
        $rules = [
            'title' => 'required|string',
            'description' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $post = Post::create($request->input());

        return response()->json([
            'status' => 201,
            'data' => $post,
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        Log::info('body', ['body' => $request->input()]);
        if (empty($post)) {
            return response()->json([
                'status' => 404,
                'error' => 'Not found this post'
            ]);
        }

        $post->update($request->input());
        return response()->json([
            'status' => 200,
            'data' => $post
        ]);
    }

    public function delete(string $id)
    {
        $post = Post::find($id);
        if (empty($post)) {
            return response()->json([
                'status' => 404,
                'error' => 'Not found this post to delete'
            ], 404);
        }

        $post->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Delete success',
            'data' => $post
        ], 200);
    }
}
