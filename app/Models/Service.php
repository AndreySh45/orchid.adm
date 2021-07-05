<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['phone', 'name', 'last_name', 'email', 'birthday', 'service_id', 'assessment'];
}
