<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->paginate(6);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //ddd($request->all());
        //Validare dati
        $validate = $request->validate([
            'title'=> 'required',
            'cover'=> 'required',
            'sub_title'=> 'required',
            'body'=> 'required',
        ]);
        //creazione slug
        $validate['slug']= Str::slug($validate['title']);
        //ddd($validate);
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
        return view ('admin.posts.edit', compact('post'));
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
        //ddd($request->all());
        //Validare dati
        $validate = $request->validate([
            'title' => ['required',Rule::unique('posts')->ignore($post->id)],
            'cover' => ['required'],
            'sub_title' => ['required'],
            'body' => ['required']
        ]);
        //creazione slug
        $validate['slug']= Str::slug($validate['title']);
        //ddd($validate);
        //salvare dati
        $post->update($validate);
        //redirect
        return redirect()->route('admin.posts.index')->with('message',' Hai modificato un nuovo post');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index');
    }
}
