<?php

/**
 * @author Zeeshan N
 * @Class Controller
 */

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Description - Creating View
     * @param string $partial
     * @param string $title
     * @param array $params
     * @author Zeeshan N
     */

    public function createView($partials, $title = "", $params = [])
    {
        $params['partials'] = 'partials.' . $partials;
        $params['title'] = $title;
        return view('partials.base')->with($params);
    }




    // public function buildResponse($message, $data)
    // {
    //     return response([
    //         'status' => 200,
    //         'message' => $message,
    //         'data' => $data,
    //     ], 200);
    // }

    // public function notfoundResponse($message)
    // {
    //     return response([
    //         'status' => 200,
    //         'message' => $message,
    //     ], 200);
    // }

    // public function tokenResponse($message, $data, $token)
    // {
    //     return response([
    //         'status' => 200,
    //         'message' => $message,
    //         'data' => $data,
    //         'token' => $token,
    //     ], 200);
    // }

    // public function loginresponse($message, $data)
    // {
    //     return response([
    //         'status' => 200,
    //         'message' => $message,
    //         'data' => $data,
    //     ], 200);
    // }

    // public function errorResponse($message)
    // {
    //     return response([
    //         'message' => $message,
    //         'status' => 500,
    //     ], 500);
    // }

    /**
     * Description - Redirecting Back with message
     * @param string $requestParam
     * @param string $path
     * @author Zeeshan N     */
    public function uploadFile($requestParam, $path)
    {
        $nameImg = Str::random(7) . '-' . time() . '.' . request($requestParam)->getClientOriginalExtension();
        request($requestParam)->move(public_path($path), $nameImg);
        return $path . $nameImg;
    }
}
