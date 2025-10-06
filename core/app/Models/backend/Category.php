<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'icon',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image'
    ];

    public function subcategory()
    {
        return $this->hasMany(Subcategory::class);
    }
}
