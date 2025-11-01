<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expense';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function expense_category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
