<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];


    /**
     * Get the route key for the model
     * 
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function posts():HasMany
    {
        return $this->hasMany(Post::class);
    }
}
