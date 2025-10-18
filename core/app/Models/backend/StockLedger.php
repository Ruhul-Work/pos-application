<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $table = 'stock_ledgers';

    protected $fillable = [
        'txn_date',
        'product_id',
        'warehouse_id',
        'ref_type',
        'ref_id',
        'direction',
        'quantity',
        'unit_cost',
        'note',
        'created_by',
    ];

    public $timestamps = true;
}