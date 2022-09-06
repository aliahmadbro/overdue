<?php

/**
 * @author Zeeshan N
 * @class Category
 */

namespace App\Http\Controllers\Admin\Customer;
use File;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\SaveRequest;
use App\Http\Requests\Admin\Banner\UpdateRequest;
use App\Models\Banner;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * @author Zeeshan N
     */
    public function __construct()
    {
        $this->partial = 'admin.customer.';
        $this->customer = new Customer();
    }

    /**
     * Description - Create Lists of Category
     * @author Zeeshan N
     */
    public function listing(Request $request)
    {
        try {
            // $category = $this->category->newQuery()->activeCategory()->paginate(PAGINATE);
            $customer = $this->customer->newQuery()->get();
            return $this->createView($this->partial . 'index', 'customer', ['customer' => $customer]);
        } catch (Exception $e) {
            session()->flash('error', __('general.error_msg'));
            return redirect()->back();
        }
    }

    /**
     * Description - Create view of Category
     * @author Zeeshan N
     */
    public function status(Request $request,$id, $status)
    {
        try {
            DB::beginTransaction();
            $customer = $this->customer->newQuery()->where('id', $id)->first();

            if (!empty($customer)) {
                $customer->verify = $status;
                if ($customer->save()) {
                    DB::commit();
                    session()->flash('success', __('general.status_updated'));
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
     * Description - Create view of Category
     * @author Zeeshan N
     */
    public function save(SaveRequest $request)
    {
        try {
            DB::beginTransaction();
            if (!empty($request->banner_image)) {
                $request['image'] = $this->uploadFile('banner_image', 'uploads/banner/');
            }
            $service = $this->banner->updateBannerDetails($request);
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
            $banner = $this->banner->newQuery()->where('id', $id)->where('status', 0)->first();
            if ($banner) {
                
                $dest = $banner->image;
                // dd(File::exists($dest));
                if (File::exists($dest)) {
                    File::delete($dest);
                }
                if ($banner->delete()) {
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
            $banner = $this->banner->newQuery()->where('id', $id)->where('status', 0)->first();
            // $services = $this->service->newQuery()->activeService()->get();
            if ($banner) {
                return $this->createView(
                    $this->partial . '.create',
                    'Banner',
                    [
                        'banner'       => $banner,
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
            $model = $this->banner->newQuery()->where('id', $request['id'])->first();
            if (!empty($request->banner_image)) {
                $request['image'] = $this->uploadFile('banner_image', 'uploads/banner/');
            }
            $banner = $model->updateBannerDetails($request);
            if ($banner) {
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
