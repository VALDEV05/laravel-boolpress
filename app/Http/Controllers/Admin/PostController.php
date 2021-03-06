<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

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
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
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
            'title'=>[ 'required', 'unique:posts', 'max:200'],
            'cover'=> ['required', 'image', 'max:200'],
            'sub_title'=> ['required'],
            'body'=> ['required'],
            'category' => ['nullable', 'exists:categories,id'],
        ]);
        if ($request->file('cover')) {

            //creazione percorso immagine
            $cover_path = Storage::put('post_images', $request->file('cover'));
    
            //passo il percorso immagine ai dati validati
            $validate['cover'] = $cover_path; 
        }

        //creazione slug
        $validate['slug'] = Str::slug($validate['title']);

        //creazione user_id
        $validate['user_id'] = Auth::id();

        //salvare dati
        $post = Post::create($validate);
        if ($request->has('tags')) {
            $request->validate([
                'tags' => ['nullable', 'exists:tags,id']
            ]);
            $post->tags()->attach($request->tags);
        }
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
            $tags = Tag::all();
            return view ('admin.posts.edit', compact('post', 'categories', 'tags'));
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
                'cover' => ['required', 'image', 'max:500'],
                'sub_title' => ['required'],
                'body' => ['required'],
                'category_id' => 'nullable|exists:categories,id',
            ]);

            if ($request->file('cover')) {
                //cancellazione percorso immagine precedente
                Storage::delete($post->cover);
                
                //creazione percorso immagine
                $cover_path = Storage::put('post_images', $request->file('cover'));
        
                //passo il percorso immagine ai dati validati
                $validate['cover'] = $cover_path; 
            }


            //creazione slug
            $validate['slug']= Str::slug($validate['title']);

            //salvare dati
            $post->update($validate);
            
            if ($request->has('tags')) {
                $request->validate([
                    'tags' => ['nullable', 'exists:tags,id']
                ]);
                $post->tags()->sync($request->tags);
            }
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
