<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;

class StockCurrent extends Model
{
    protected $table = 'stock_currents';

    protected $fillable = [
    'product_id','warehouse_id','branch_id','quantity',
    ];

    public $timestamps = true;
}