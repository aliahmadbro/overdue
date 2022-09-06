<?php

/**
 * @author Zeeshan N
 * @class Category
 */

namespace App\Http\Controllers\Admin\UserSupport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserSupport\SaveRequest;
use App\Http\Requests\Admin\UserSupport\UpdateRequest;
use App\Models\Support;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSupportController extends Controller
{
    /**
     * @author Zeeshan N
     */
    public function __construct()
    {
        $this->partial = 'admin.userSupport.';
        $this->support = new Support();
    }

    /**
     * Description - Create Lists of Category
     * @author Zeeshan N
     */
    public function listing(Request $request)
    {
        try {
            // $category = $this->category->newQuery()->activeCategory()->paginate(PAGINATE);
            $user = $this->support->newQuery()->where('support','user')->get();
            return $this->createView($this->partial . 'index', 'UserSupport', ['user' => $user]);
        } catch (Exception $e) {
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }

    /**
     * Description - Create view of Category
     * @author Zeeshan N
     */
    public function create(Request $request)
    {
        try {
            return $this->createView(
                $this->partial . '.create',
                'User Support',
            );
        } catch (Exception $e) {
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }

    /**
     * Description - Create view of Category
     * @author Zeeshan N
     */
    public function save(SaveRequest $request)
    {
        try {
            DB::beginTransaction();
            $request['support'] ='user';
            $user = $this->support->updateSupportDetails($request);
                DB::commit();
                session()->flash('success', __('general.updated'));
                return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }

    /**
     * Description - Delete Category
     * @author Zeeshan N
     */
    public function delete(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = $this->support->newQuery()->where('id', $id)->first();
            if ($user) {
                if ($user->delete()) {
                    DB::commit();
                    session()->flash('error', __('general.deleted'));
                    return redirect()->back();
                }
            }
            DB::rollBack();
            session()->flash('error', __('general.error_updating'));
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }

    /**
     * Description - Edit view of Category
     * @author Zeeshan N
     */
    public function edit(Request $request, $id)
    {
        try {
            // $category = $this->category->newQuery()->where('id', $id)->activeCategory()->first();
            $user = $this->support->newQuery()->where('id', $id)->first();
            // $services = $this->service->newQuery()->activeService()->get();
            if ($user) {
                return $this->createView(
                    $this->partial . '.create',
                    'User Support',
                    [
                        'user'=> $user,
                    ]
                );
            }
        } catch (Exception $e) {
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }

    /**
     * Description - Updae Category
     * @author Zeeshan N
     */
    public function update(UpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $model = $this->support->newQuery()->where('id', $request['id'])->first();
            $request['support'] ='user';
            $support= $model->updateSupportDetails($request);
            if ($support) {
                DB::commit();
                session()->flash('success', __('general.updated'));
                return redirect()->back();
            }

            DB::rollBack();
            session()->flash('error', __('general.error_updating'));
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }
}
