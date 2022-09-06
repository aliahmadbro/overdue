<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $guarded = [];
    use HasFactory;

    protected function setImageAttribute($value)
    {
        $this->attributes['image'] = env('MEDIA_URL') . $value;
    }
}
