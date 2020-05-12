<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'id', 'parent_id');
    }

    public function childCategories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
}
