<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, BaseModel;

    protected $fillable = [
        'name',
        'description',
        'image',
        'banner',
    ];

    protected $table = 'categories';

    protected function setImageAttribute($value)
    {
        $this->attributes['image'] = env('MEDIA_URL') . $value;
    }
    protected function setBannerAttribute($value)
    {
        $this->attributes['banner'] = env('MEDIA_URL') . $value;
    }
    /**
     * Get all of the ParentCategory for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'id', 'parent_id');
    }

    /**
     * Get all of the ParentCategory for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    /**
     * Scope to fetch only Parent Category
     * @author Zeeshan N
     */
    public function scopeFetchParent($query)
    {
        $query->where('parent_id',0);
    }

    /**
     * Fetch Active Status
     * @author Zeeshan N
     */
    public function scopeActiveCategory($query)
    {
        $query->where('status', 1);
    }

    /**
     * Description - Save / Update Category Info
     * @param array $request
     * @author Zeeshan N
     */
    public function updateCategoryDetails($request)
    {
        $this->parent_id = $request['parent_id'];
        return $this->saveUpdateInfo($this, $request->only('name', 'description', 'parent_id', 'image'));
    }
}
