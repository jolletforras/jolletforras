<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'activation_code', 'user_id'
    ];

}
