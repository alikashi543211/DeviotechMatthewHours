<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkCategory;
use App\Rules\ValidArrayRule;

class WorkCategoryController extends Controller
{
    public function index()
    {
    	$list=WorkCategory::all();
    	return view("admin.work_category.index",get_defined_vars());
    }

    public function add()
    {
    	return view("admin.work_category.add");
    }

    public function edit(Request $req)
    {
    	$item=WorkCategory::find($req->id);
    	return view("admin.work_category.edit",get_defined_vars());
    }

    public function save(Request $req)
    {
    	$messages = [
            'name.required' => 'Enter work category',
        ];
        $rules = [
            'name' => ['required'],
        ];
        $this->validate($req,$rules,$messages);
    	if(isset($req->id))
    	{
			WorkCategory::where('id',$req->id)->update(['name'=>$req->name]);
    	}else
    	{
    		WorkCategory::create(['name'=>$req->name]);
    		
    	}
    	return redirect()->route('admin.work_category.list')->with("success","Saved successfully");
    }

    public function delete(Request $req)
    {
    	WorkCategory::find($req->id)->delete();
    	return back()->with("success","Deleted successfully");
    }
}
