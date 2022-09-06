<?php

/**
 * @author Zeeshan N
 * @class Category
 */

namespace App\Http\Controllers\Admin\Category;
use File;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\SaveRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Category;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * @author Zeeshan N
     */
    public function __construct()
    {
        $this->partial = 'admin.category.';
        $this->category = new Category();
        $this->service = new Service();
    }

    /**
     * Description - Create Lists of Category
     * @author Zeeshan N
     */
    public function listing(Request $request)
    {
        try {
            // $category = $this->category->newQuery()->activeCategory()->paginate(PAGINATE);
            $category = $this->category->newQuery()->where('status', 0)->get();
            return $this->createView($this->partial . 'index', 'Category', ['category' => $category]);
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
            $parentCategory = $this->category->newQuery()->fetchParent()->get();
            return $this->createView(
                $this->partial . '.create',
                'Category',
                ['parentCategory' => $parentCategory]
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
            if (!empty($request->category_image)) {
                $request['image'] = $this->uploadFile('category_image', 'uploads/category/');
            }
            if (!isset($request['sub_category'])) {
                $request['parent_id'] = 0;
            }
            $service = $this->category->updateCategoryDetails($request);
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
            $category = $this->category->newQuery()->where('id', $id)->where('status', 0)->first();
            if ($category) {
                $dest = $category->image;
                if (File::exists($dest)) {
                    File::delete($dest);
                }
                if ($category->delete()) {
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
            $category = $this->category->newQuery()->where('id', $id)->where('status', 0)->first();
            $parentCategory = $this->category->newQuery()->fetchParent()->where([['id', '!=', $id]])->get();
            // $services = $this->service->newQuery()->activeService()->get();
            if ($category) {
                return $this->createView(
                    $this->partial . '.create',
                    'Category',
                    [
                        'parentCategory' => $parentCategory,
                        'category'       => $category,
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
            $model = $this->category->newQuery()->where('id', $request['id'])->first();
            if (!empty($request->category_image)) {
                $request['image'] = $this->uploadFile('category_image', 'uploads/category/');
            }
            if (!isset($request['sub_category'])) {
                $request['parent_id'] = 0;
            }
            $service = $model->updateCategoryDetails($request);
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
