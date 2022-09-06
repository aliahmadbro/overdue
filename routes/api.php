    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Api\CustomerController;
    use App\Http\Controllers\Api\ProviderController;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
    */



    Route::group(['middleware' => 'auth:sanctum'], function () {

        // Route::prefix('admin')->group(function () {});


    });


    // customer routes.................. 

    Route::post('customer-register', [CustomerController::class, 'register']);
    Route::post('customer-login', [CustomerController::class, 'login']);
    Route::get('category', [CustomerController::class, 'Category']);
    Route::get('home', [CustomerController::class, 'Home']);
    Route::post('sub-category', [CustomerController::class, 'subCategory']);
    Route::post('service', [CustomerController::class, 'Service']);
    Route::post('sub-service', [CustomerController::class, 'subService']);
    Route::get('banner', [CustomerController::class, 'Banner']);
    Route::post('profile', [CustomerController::class, 'Profile']);
    Route::post('update-profile', [CustomerController::class, 'updateProfile']);
    Route::post('post-job', [CustomerController::class, 'postJob']);
    Route::post('search-category', [CustomerController::class, 'searchCategory']);
    Route::post('search-sub-category', [CustomerController::class, 'searchSubCategory']);
    Route::get('user-support', [CustomerController::class, 'Support']);


    // provider routes.................. 

    Route::post('provider-register', [ProviderController::class, 'register']);
    Route::post('provider-login', [ProviderController::class, 'login']);
    Route::post('provider-profile', [ProviderController::class, 'Profile']);
    Route::post('provider-update-profile', [ProviderController::class, 'updateProfile']);
    Route::get('provider-support', [ProviderController::class, 'Support']);
