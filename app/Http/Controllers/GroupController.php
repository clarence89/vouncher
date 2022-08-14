<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class GroupController extends Controller
{
    public function get_group(Request $request){
        $id = $request->user()->id;
        if ($request->user()->hasRole('super-admin')) {
            $roles = Role::where('name', '!=','super-admin')->paginate(5);
            return $roles;
        }else{
            $roles = Role::whereHas('role_admins',function($query)use($id){
                $query->where('user_id',$id);
            })->paginate(5);
        }
        return  $roles;

    }
    public function get_groupuser(Request $request){
        $users = Role::where('name',$request->group_name)->first();
        return  $users->users()->paginate(10);

    }
    public function remove_user_group(Request $request){
        $users = User::where('id',$request->user_id)->first();
        $users->removeRole($request->group_name);
        return  $request->all();
    }

}
