<?php

namespace App\Models;

use App\Policies\ModelPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class City extends Model
{
    use HasFactory, HasRoles;
    /**
     * The attributes guard.
     *
     * @var string
     */
    protected string $guard_name = "api";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'active',
    ];
}
