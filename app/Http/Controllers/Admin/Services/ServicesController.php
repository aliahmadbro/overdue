<?php

/**
 * @Author Zeeshan N
 * @Class service
 */

namespace App\Http\Controllers\Admin\Services;

use Exception;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Service\SaveRequest;
use App\Http\Requests\Admin\Service\UpdateRequest;

class ServicesController extends Controller
{
    /**
     * @author Zeeshan N
     */
    public function __construct()
    {
        $this->partial = 'admin.services.';
        $this->service = new Service();
        $this->category = new Category();
    }

    /**
     * Description - Create Lists of Service
     * @author Zeeshan N
     */
    public function listing(Request $request)
    {
        try {
            // $services = $this->service->newQuery()->activeService()->paginate(PAGINATE);
            $services = $this->service->newQuery()->where('status',0)->get();
            return $this->createView(
                $this->partial . 'index',
                'Services',
                ['services' => $services]
            );
        } catch (\Throwable $e) {
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }

    /**
     * Description - Create view of Service
     * @author Zeeshan N
     */
    public function create(Request $request)
    {
        try {
            $parentService = $this->service->newQuery()->fetchParent()->get();
            $subCategory = $this->category->newQuery()->whereNot('parent_id',0)->get();
            return $this->createView($this->partial . '.create', 'Services', ['parentService' => $parentService,'subCategory'=>$subCategory]);
        } catch (Exception $e) {
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }

    /**
     * Description - Store Service
     * @author Zeeshan N
     */
    public function save(SaveRequest $request)
    {
        try {
            DB::beginTransaction();
            if (!empty($request->service_image)) {
                $request['image'] = $this->uploadFile('service_image', 'uploads/services/');
            }
            if (!isset($request['sub_service'])) {
                $request['parent_id'] = 0;
            }

            $service = $this->service->updateServiceDetails($request);
            if ($service) {
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


    /**
     * Description - Delete Service
     * @author Zeeshan N
     */
    public function delete(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            // $service = $this->service->newQuery()->where('id', $id)->activeService()->first();
            $service = $this->service->newQuery()->where('id', $id)->where('status', 0)->first();
            $dest = $service->image;
            if (File::exists($dest)) {
                File::delete($dest);
            }
                if ($service->delete()) {
                    DB::commit();
                    session()->flash('error', __('general.deleted'));
                    return redirect()->back();
                }
            DB::rollBack();
            session()->flash('error', __('general.error_updating'));
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }


    /**
     * Description - Edit view of Service
     * @author Zeeshan N
     */
    public function edit(Request $request, $id)
    {
        try {
            // $service = $this->service->newQuery()->where('id', $id)->activeService()->first();
            $service = $this->service->newQuery()->where('id', $id)->where('status', 0)->first();
            $parentService = $this->service->newQuery()->fetchParent()->where('id', '!=', $id)->get();
            $subCategory=$this->category->newQuery()->whereNot('parent_id',0)->get();
            if ($service) {
                return $this->createView(
                    $this->partial . '.create',
                    'Services',
                    [
                        'parentService' => $parentService,
                        'service'       => $service,
                        'subCategory'   => $subCategory,
                    ]
                );
            }
            session()->flash('error', 'Service Not Found');
            return redirect()->back();
        } catch (Exception $e) {
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }

    /**
     * Description - Updae Service
     * @author Zeeshan N
     */
    public function update(UpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $model = $this->service->newQuery()->where('id', $request['id'])->first();
            if (!empty($request->service_image)) {
                $request['image'] = $this->uploadFile('service_image', 'uploads/services/');
            }
            if (!isset($request['sub_service'])) {
                $request['parent_id'] = 0;
            }

            $service = $model->updateServiceDetails($request);
            if ($service) {
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
