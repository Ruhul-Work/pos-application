<?php
namespace App\Models\backend;

use App\Models\backend\Role;
use App\Models\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasPermissions;

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'phone', 'username', 'password',
        'role_id', 'branch_id', 'status', 'meta', 'remember_token',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login'        => 'datetime',
        'meta'              => 'array',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function branch()
    {
        return $this->belongsTo(\App\Models\backend\Branch::class, 'branch_id');
    }
}
