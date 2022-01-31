<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Form aggiunto all'interno di admin.categories.index
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
            'name'=> 'required'
        ]);

        //creazione slug
        $validate['slug']= Str::slug($validate['name']);

        //ddd
        //ddd($request->all(), $validate);

        //salvataggio
        Category::create($validate);
        //return
        return redirect()->route('admin.categories.index')->with('message', '🥳 Complimenti hai implementato una nuova categoria');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //ddd($request->all());

        //validazione
        $validate = $request->validate([
            'name'=> 'required'
        ]);

        //creazione slug
        $validate['slug']= Str::slug($validate['name']);

        //ddd
        //ddd($request->all(), $validate);

        //salvataggio
        $category->update($validate);
        //return
        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('message', '😱 Hai rimosso una categoria per sempre!!');
        
    }
}
