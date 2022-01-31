<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = ['title', 'cover', 'sub_title', 'body', 'slug','category_id', 'user_id'];

    /**
    * Get all of the category for the post
    * 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function category(): BelongsTo   
    {
        return $this->belongsTo(Category::class);
    }

    
    /**
    * Get all of the user for the post
    * 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function user(): BelongsTo   
    {
        return $this->belongsTo(User::class);
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
