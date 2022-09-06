<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];
    use HasFactory;

    protected function getImageAttribute($value)
    {
        return env('ADMIN_URL') . $value;
    }
}
