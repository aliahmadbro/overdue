<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivacyPolicy extends Model
{
    use HasFactory, BaseModel;

    protected $fillable = [
        'support',
        'description',
    ];

    public function updatePrivacyPolicyDetails($request)
    {
        return $this->saveUpdateInfo($this,$request->only('support', 'description'));
    }
}
