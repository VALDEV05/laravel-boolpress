<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::user()->posts()->orderByDesc('id')->paginate(6);
        //$posts = Post::orderByDesc('id')->paginate(6);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validare dati
         $validate = $request->validate([
            'title'=> 'required',
            'cover'=> 'required',
            'sub_title'=> 'required',
            'body'=> 'required',
            'category' => 'nullable | exists:categories,id',
        ]);

        //creazione slug
        $validate['slug']= Str::slug($validate['title']);

        //creazione user_id
        $validate['user_id'] = Auth::id();


        //salvare dati
        Post::create($validate);

        //redirect
        return redirect()->route('admin.posts.index')->with('message',' Hai creato un nuovo post'); 
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response  
     */
    public function edit(Post $post)
    {
        if (Auth::id() == $post->user_id) {
            $categories = Category::all();
            return view ('admin.posts.edit', compact('post', 'categories'));
        }else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if (Auth::id() == $post->user_id) {
             //Validare dati
            $validate = $request->validate([
                'title' => ['required',Rule::unique('posts')->ignore($post->id)],
                'cover' => ['required'],
                'sub_title' => ['required'],
                'body' => ['required'],
                'category_id' => 'nullable|exists:categories,id',
            ]);

            //creazione slug
            $validate['slug']= Str::slug($validate['title']);

            //salvare dati
            $post->update($validate);
            
            //redirect
            return redirect()->route('admin.posts.index')->with('message',' Hai modificato un nuovo post');
        }else{
            abort(403);
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (Auth::id() == $post->user_id) {
            $post->delete();
            return redirect()->route('admin.posts.index');
        }else{
            abort(403);
        }
    }
}
