<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $guarded = [];
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'address',
        'budget',
        'date',
        'service_id',
        'cus_id'
    ];

    protected function setImageAttribute($value)
    {
        $this->attributes['image'] = env('MEDIA_URL') . $value;
    }
}
