<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory, BaseModel;

    protected $fillable = [
        'question',
        'answer',
    ];

    protected $table = 'supports';

    protected function setImageAttribute($value)
    {
        $this->attributes['image'] = env('MEDIA_URL') . $value;
    }
    /**
     * Scope to fetch only User Support
     * @author Zeeshan N
     */
    public function scopeFetchUser($query)
    {
        $query->where('support','user');
    }

        /**
     * Scope to fetch only Provider Support
     * @author Zeeshan N
     */

    public function scopeFetchProvider($query)
    {
        $query->where('support','provider');
    }


    /**
     * Description - Save / Update Category Info
     * @param array $request
     * @author Zeeshan N
     */
    public function updateSupportDetails($request)
    {
        $this->support = $request['support'];
        return $this->saveUpdateInfo($this, $request->only('question', 'answer', 'support'));
    }
}
