<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;

class JournalEntryLine extends Model
{
    protected $table = 'journal_entry_lines';
    
    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'debit',
        'credit',
        'description',
    ];
}
