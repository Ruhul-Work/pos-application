<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','code','sort','is_active'];
    protected $casts = ['is_active'=>'boolean','sort'=>'integer'];

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}