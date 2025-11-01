<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = ['cost_price' => 'float'];

    public function product_type()
    {
        return $this->belongsTo(ProductType::class);
    }
    public function category_type()
    {
        return $this->belongsTo(CategoryType::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
    public function paper_quality()
    {
        return $this->belongsTo(PaperQuality::class,'paper_id');
    }

    public function children()
    {
        return $this->hasMany(Product::class, 'parent_id', 'id');
    }



    
}
