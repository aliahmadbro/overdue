<?php

/**
 * @author Zeeshan N
 * @class Category
 */

namespace App\Http\Controllers\Admin\ProviderSupport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProviderSupport\SaveRequest;
use App\Http\Requests\Admin\ProviderSupport\UpdateRequest;
use App\Models\Support;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;
class ProviderSupportController extends Controller
{
    /**
     * @author Zeeshan N
     */
    public function __construct()
    {
        $this->partial = 'admin.providerSupport.';
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
            $provider = $this->support->newQuery()->where('support','provider')->get();
            return $this->createView($this->partial . 'index', 'Provider Support', ['provider' => $provider]);
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
                'Provider Support',
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
            $request['support'] ='provider';
            $service = $this->support->updateSupportDetails($request);
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
            $provider = $this->support->newQuery()->where('id', $id)->first();
            if ($provider) {
                if ($provider->delete()) {
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
            $provider = $this->support->newQuery()->where('id', $id)->first();
            // $services = $this->service->newQuery()->activeService()->get();
            if ($provider) {
                return $this->createView(
                    $this->partial . '.create',
                    'Provider Support',
                    [
                        'provider'       => $provider,
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
            $request['support'] ='provider';
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
