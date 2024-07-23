<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::where('user_id',Auth::id())->get();
        if ($posts)
        {
            return $posts;

        }
        else
        {
            return ['error' => 'لا توجد أي منشورات'];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validatedData = $request->validated();

        Auth::user()->posts()->create($validatedData);

        return "تم أنشاء المنشور بنجاح";

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        Gate::authorize('view',$post);
        $post = Post::find($post->id);

        if($post)
        {
            return $post;
        }

        return response()->json(['error' => 'المنشور غير موجود'], 404);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request,Post $post)
    {
    Gate::authorize('update',$post);


        $post = Post::find($request->id);
        if($post)
        {

            $validatedData = $request->validated();
            $post->update($request->only('title', 'content'),['user_id'=>auth()->user()->id]);

            return "تم تحديث بيانات المنشور بنجــــاح";
        }
        else
        {
            return response()->json(['error' => 'المنشور غير  موجود'], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        Gate::authorize('delete',$post);
        $post = Post::find($request->id);
        if($post)
        {
            $post->delete();

            return "تم حذف المنشور بنجــــاح";
        }
        else
        {

            return 'المنشور غير موجود';
        }
    }
}
