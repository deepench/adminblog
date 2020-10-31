<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        // first check if you are loggedin and admin user ...
        //return Auth::check();

        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }

        if (!Auth::check() && $request->path() == 'login') {

            return view('welcome');
        }
        // you are already logged in... so check for if you are an admin user..
        $user = Auth::user();
        if ($user->userType == 'User') {
            return redirect('/login');
        }
        if ($request->path() == 'login') {
            return redirect('/');
        }
        // return $this->checkForPermission($user, $request);

        return view('welcome');
    }
    // public function checkForPermission($user, $request)
    // {
    //     $permission = json_decode($user->role->permission);
    //     // echo $permission[0]->name;
    //     // echo  $request->path();
    //     //     die();
    //     $hasPermission = false;
    //     if (!$permission) {
    //         return view('welcome');
    //     }

    //     foreach ($permission as $p) {
    //         if ($p->name == $request->path()) {
    //             if ($p->read) {
    //                 $hasPermission = true;
    //             }
    //         }
    //     }
    //     if ($hasPermission) {
    //         return view('welcome');
    //     }
    //     return view('welcome');
    //     return view('notfound');
    // }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
    public function adminlogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'bail|required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            // return $user->role;
            // \Log::info($user->role->roleName);
            // return;
            if ($user->role->isAdmin == 0) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect login details',
                ], 401);
            }
            return response()->json([
                'msg' => 'You are logdded in',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'msg' => 'incorrect details'
            ]);
        }
    }
}
