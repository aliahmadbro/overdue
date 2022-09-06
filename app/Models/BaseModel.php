<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

/**
 * Description - BASE Model to handle common functions
 * @author Zeeshan N
 */
trait BaseModel
{

    /**
     * Description - Saving Model Info
     * @author Zeeshan N
     */
    public function saveUpdateInfo($model, $param)
    {
        $model->fill($param);
        if ($model->save()) {
            return $model;
        }

        return false;
    }

    /**
     * Fetch Active Status
     * @author Zeeshan N
     */
    public function scopeActiveStatus($query)
    {
        $query->where('status', ACTIVE);
    }

    /**
     * Fetch Custome Status
     * @author Zeeshan N
     */
    public function scopeCustomStatus($query, $status)
    {
        $query->whereIn('status', $status);
    }
}
