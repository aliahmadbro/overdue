<?php

/**
 * @Author Zeeshan N
 * @Class User
 */

namespace App\Http\Controllers\Admin\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Description - Create Lists of Users
     * @author Zeeshan N
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('backend.admin.user.index', compact('users'));
    }

    /**
     * Description - Edit view of User
     * @author Zeeshan N
     */
    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        return view('backend.admin.user.create', compact('user'));
    }
    /**
     * Description - Updae User
     * @author Zeeshan N
     */
    public function update(Request $request)
    {
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $dest = $user->image;
            if (File::exists($dest)) {
                File::delete($dest);
            }
            $filename = time() . Str::slug('') . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/upload/profile');
            $request->image->move($destinationPath, $filename);
            $user->image = 'upload/profile/' . $filename;
        }
        $user->status = $request->input('status') == true ? '1' : '0'; //0=show | 1=hide
        $user->update();
        return redirect()->route('admin.user')->with('message', 'User Updated Successfully');
    }
    /**
     * Description - Delete User
     * @author ZeeshanN
     */
    public function delete($id)
    {
        $user = User::find($id);
        $dest = $user->image;
        if (File::exists($dest)) {
            File::delete($dest);
        }
        $user->delete();
        return redirect()->route('admin.user')->with('message', 'User Deleted Successfully');
    }
}
