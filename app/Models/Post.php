<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = ['title', 'cover', 'sub_title', 'body', 'slug'];

    public function category(): BelongsTo   
    {
        return $this->belongsTo(Category::class);
    }




    /**
     * Get the route key for the model
     * 
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
