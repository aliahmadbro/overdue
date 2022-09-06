<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use BaseModel;

    protected $table = 'services';
    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    protected function setImageAttribute($value)
    {
        $this->attributes['image'] = env('MEDIA_URL') . $value;
    }
    /**
     * Get all of the ParentService for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */

    public function parentService()
    {
        return $this->belongsTo(Service::class, 'parent_id', 'id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_id', 'id');
    }


    public function scopeFetchParent($query)
    {
        $query->whereNull('parent_id');
    }

    /**
     * Fetch Active Status
     */
    public function scopeActiveService($query)
    {
        $query->where('status', ACTIVE);
    }

    /**
     * Description - Save / Update Service Info
     * @param array $request
     * @author Zeeshan N
     */
    public function updateServiceDetails($request)
    {
        $this->parent_id = $request['parent_id'];
        $this->sub_id = $request['sub_id'];
        return $this->saveUpdateInfo($this, $request->only('name', 'parent_id','sub_id', 'image', 'description',));
    }
}
