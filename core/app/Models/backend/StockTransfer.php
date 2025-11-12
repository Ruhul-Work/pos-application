<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockTransfer extends Model
{
    use HasFactory;

    protected $table = 'stock_transfers';

    protected $fillable = [
        'reference_no',
        'from_warehouse_id',
        'from_branch_id',
        'to_warehouse_id',
        'to_branch_id',
        'transfer_date',
        'status',
        'note',
        'created_by',
    ];


    protected $dates = [
        'transfer_date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // সম্পর্ক: এক ট্রান্সফারে একাধিক আইটেম থাকে
    public function items()
    {
        return $this->hasMany(StockTransferItem::class, 'transfer_id');
    }

  
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
