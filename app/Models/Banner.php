<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory,BaseModel;

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    protected $table = 'banners';

    protected function setImageAttribute($value)
    {
        $this->attributes['image'] = env('MEDIA_URL') . $value;
    }
    /**
     * Get all of the ParentService for the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */

    public function parentBanner()
    {
        return $this->belongsTo(Banner::class, 'parent_id', 'id');
    }

    public function scopeFetchParent($query)
    {
        $query->whereNull('parent_id');
    }

    /**
     * Fetch Active Status
     */
    public function scopeActiveBanner($query)
    {
        $query->where('status', ACTIVE);
    }

    /**
     * Description - Save / Update Service Info
     * @param array $request
     * @author Zeeshan N
     */
    public function updateBannerDetails($request)
    {
        return $this->saveUpdateInfo($this, $request->only('name', 'image', 'description',));
    }
}
