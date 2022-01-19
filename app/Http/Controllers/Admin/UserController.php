<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recruiter;

class UserController extends Controller
{
    public function index()
    {
    	$list=Recruiter::get();
    	return view("admin.user.index",get_defined_vars());
    }

    public function add()
    {
    	return view("admin.user.add");
    }

    public function edit(Request $req)
    {
    	$item=Recruiter::find($req->id);
    	return view("admin.user.edit",get_defined_vars());
    }

    public function save(Request $req)
    {
    	$messages = [
            'name.required' => 'Enter user name',
        ];
        $rules = [
            'name' => ['required'],
        ];
        $this->validate($req,$rules,$messages);
    	if(isset($req->id))
    	{
			Recruiter::where('id',$req->id)
                ->update(['name'=>$req->name]);
    		
    	}else
    	{
    		Recruiter::create(['name'=>$req->name]);
    	}
    	return redirect()->route('admin.user.list')
            ->with("success","Saved successfully");
    }

    public function delete(Request $req)
    {
    	Recruiter::find($req->id)->delete();
    	return back()->with("success","Deleted successfully");
    }
}
