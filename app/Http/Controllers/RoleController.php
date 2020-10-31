<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return Role::latest()->get();
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required',
        ]);
        return Role::create([
            'roleName' => $request['roleName'],

        ]);
    }
    public function update(Request $request, $id)
    {
        $role = Role::findorFail($id);
        $this->validate($request, [
            'roleName' => 'required',
        ]);

        $role->update($request->all());
        return ['message' => 'update the role info'];
    }
    public function destroy($id)
    {
        $role = Role::findorFail($id);

        //delete the role
        $role->delete();
    }

    public function search()
    {

        if ($search = \Request::get('q')) {
            $roles = Role::where(function ($query) use ($search) {
                $query->where('roleName', 'LIKE', "%$search%");
            })->paginate(20);
        } else {
            $roles = Role::latest()->paginate(5);
        }
        return $roles;
    }

    public function assignrole(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'permission' => 'required',
        ]);
        return Role::where('id', $request->id)->update([
            'permission' => $request->permission
        ]);
    }
}
