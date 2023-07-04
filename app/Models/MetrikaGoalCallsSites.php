<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class MetrikaGoalCallsSites extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'metrika_goals_call_sites';
    /**
     * The attributes guard.
     *
     * @var string
     */
    protected string $guard_name = "api";
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'counter',
        'goal',
    ];
}
