<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $page_title = 'All Roles';
        $empty_message = 'No roles';
        $roles = Role::orderBy('id','DESC')->paginate(getPaginate());
        return view('admin.roles.index',compact('page_title', 'empty_message', 'roles'));
    }

    public function create()
    {
        $page_title = 'Create Role';
        $permission = Permission::get();
        return view('admin.roles.create',compact('page_title', 'permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create([
            'guard_name' => 'admin',
            'name' => $request->input('name')
        ]);
        $role->syncPermissions($request->input('permission'));

        $notify[] = ['success', 'Role created successfully'];
        return redirect()->route('admin.roles.all')->withNotify($notify);
    }

    public function edit($id)
    {
        $page_title = 'Edit Role';
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('admin.roles.edit',compact('page_title', 'role','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        $notify[] = ['success', 'Role updated successfully'];
        return redirect()->route('admin.roles.all')->withNotify($notify);
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        $notify[] = ['success', 'Role deleteed successfully'];
        return redirect()->route('admin.roles.all')->withNotify($notify);
    }
}
