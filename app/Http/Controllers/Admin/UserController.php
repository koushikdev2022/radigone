<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $page_title = 'All Admins';
        $empty_message = 'No Admins';
        $data = Admin::orderBy('id','DESC')->paginate(getPaginate());
        return view('admin.auth.index',compact('page_title', 'empty_message', 'data'));
    }

    public function create()
    {
        $page_title = 'Create Admin';
        $roles = Role::pluck('name','name')->all();
        return view('admin.auth.create',compact('page_title', 'roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = Admin::create($input);
        $user->assignRole($request->input('roles'));

        $notify[] = ['success', 'Admin created successfully'];
        return redirect()->route('admin.admins.all')->withNotify($notify);
    }

    public function edit($id)
    {
        $page_title = 'Edit Admins';
        $user = Admin::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('admin.auth.edit',compact('page_title', 'user','roles','userRole'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = Admin::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        $notify[] = ['success', 'Admin updated successfully'];
        return redirect()->route('admin.admins.all')->withNotify($notify);
    }

    public function destroy($id)
    {
        Admin::find($id)->delete();
        $notify[] = ['success', 'Admin deleted successfully'];
        return redirect()->route('admin.admins.all')->withNotify($notify);
    }
}
