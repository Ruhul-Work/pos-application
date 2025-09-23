<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\backend\BusinessType;

class Branch extends Model
{
    use SoftDeletes;

    protected $table = 'branches';
    protected $fillable = [
        'name',
        'code',
        'phone',
        'email',
        'address',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];


    public function businessTypes()
{
    return $this->belongsToMany(BusinessType::class, 'branch_business');
}
}
