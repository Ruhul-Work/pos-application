<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    protected $table = 'business_types';
    protected $fillable = ['name'];

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_business');
    }
}
