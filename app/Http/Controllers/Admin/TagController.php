<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view ('admin.tags.index', compact('tags'));
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
    
        //validazione
        $validate = $request->validate([
            'name' => 'required',
        ]);
        //creazione slug
        $validate['slug']= Str::slug($validate['name']);
        //ddd($request->all(), $validate);
        //salvataggio
        Tag::create($validate);
        //return redirect message
        return redirect()->route('admin.tags.index')->with('message', '🥳 Complimenti hai implementato una nuovo tag');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //validazione
        $validate = $request->validate([
            'name'=> 'required'
        ]);

        //creazione slug
        $validate['slug']= Str::slug($validate['name']);

        //ddd
        //ddd($request->all(), $validate);

        //salvataggio
        $tag->update($validate);
        //return
        return redirect()->route('admin.tags.index')->with('message', '🥳 Complimenti hai modificato una nuovo tag');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('message', '😱 Hai rimosso una categoria per sempre!!');

    }
}
