<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        return User::latest()->paginate(5);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'fullName' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6'
        ]);
        return User::create([
            'fullName' => $request['fullName'],
            'email' => $request['email'],
            'bio' => $request['bio'],
            'role_id' => $request['role_id'],
            'password' => Hash::make($request['password']),
        ]);
    }
    public function update(Request $request, $id)
    {
        $tag = User::findorFail($id);
        $this->validate($request, [
            'fullName' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|',
            'password' => 'required|string|min:6'
        ]);

        $tag->update($request->all());
        return ['message' => 'update the User info'];
    }
    public function destroy($id)
    {
        $tag = User::findorFail($id);

        //delete the tag
        $tag->delete();
    }

    public function search()
    {

        if ($search = \Request::get('q')) {
            $users = User::where(function ($query) use ($search) {
                $query->where('fullName', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
                // ->orWhere('userType', 'LIKE', "%$search%");
            })->paginate(20);
        } else {
            $users = User::latest()->paginate(5);
        }
        return $users;
    }
}
