<?php

/**
 * @Author Zeeshan N
 * @Class Dashboard
 */

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->partial = 'admin.dashboard.index';
    }

    /**
     * Description - Create view of Dashboard
     */
    public function index(Request $request)
    {
        return $this->createView($this->partial, 'Dashboard');
    }
}
