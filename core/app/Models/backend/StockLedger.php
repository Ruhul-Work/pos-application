<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $table = 'stock_ledgers';

    protected $fillable = [
    'txn_date','product_id','warehouse_id','branch_id',
    'ref_type','ref_id','direction','quantity','unit_cost',
    'note','created_by',
    ];
    
    protected $casts = [
    'txn_date' => 'datetime',
    ];

    
    public $timestamps = true;

    
    // ---- Relationships (ADD THESE) ----
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by'); 
    }
}