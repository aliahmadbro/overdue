<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;

class HomeController extends Controller
{

    public function userPrivacyPolicy(){
        $privacyPolicy=PrivacyPolicy::where('support','user')->first();
        return view('partials.app.userPrivacyPolicy',compact('privacyPolicy'));
    }
    public function providerPrivacyPolicy(){
        $privacyPolicy=PrivacyPolicy::where('support','provider')->first();
        return view('partials.app.providerPrivacyPolicy',compact('privacyPolicy'));
        
    }
}
