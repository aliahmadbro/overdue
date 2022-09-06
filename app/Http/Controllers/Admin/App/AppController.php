<?php

/**
 * @author Zeeshan N
 * @class Category
 */

namespace App\Http\Controllers\Admin\App;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use App\Http\Requests\Admin\App\SaveRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    /**
     * @author Zeeshan N
     */
    public function __construct()
    {
        $this->partial = 'admin.privacyPolicy.';
        $this->PrivacyPolicy = new PrivacyPolicy();
    }


    /**
     * Description - Create view of Category
     * @author Zeeshan N
     */
    public function create(Request $request)
    {
        try {
            $privacyPolicy=$this->PrivacyPolicy->newQuery()->where('support','provider')->first();
            if(!empty($privacyPolicy)){
                
                return $this->createView(
                    $this->partial . 'providerCreate',
                    'Privacy Policy',
                    ['privacyPolicy'=> $privacyPolicy,]
                );
            }
            return $this->createView(
                $this->partial . 'providerCreate',
                'Privacy Policy',
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
            $model=$this->PrivacyPolicy->newQuery()->where('support','provider')->first();
            $request['support'] ='provider';
            if(!empty($model)){
                $model->updatePrivacyPolicyDetails($request);
            }else{
            $service = $this->PrivacyPolicy->updatePrivacyPolicyDetails($request);
            }
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

    public function userCreate(Request $request)
    {
        try {
            $privacyPolicy=$this->PrivacyPolicy->newQuery()->where('support','user')->first();
            if(!empty($privacyPolicy)){
                
                return $this->createView(
                    $this->partial . 'userCreate',
                    'Privacy Policy',
                    ['privacyPolicy'=> $privacyPolicy,]
                );
            }
            return $this->createView(
                $this->partial . 'userCreate',
                'Privacy Policy',
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
    public function userSave(SaveRequest $request)
    {
        try {
            DB::beginTransaction();
            $model=$this->PrivacyPolicy->newQuery()->where('support','user')->first();
            $request['support'] ='user';
            if(!empty($model)){
                $model->updatePrivacyPolicyDetails($request);
            }else{
            $service = $this->PrivacyPolicy->updatePrivacyPolicyDetails($request);
            }
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


}
