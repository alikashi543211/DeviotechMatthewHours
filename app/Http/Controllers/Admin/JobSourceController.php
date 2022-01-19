<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobSource;

class JobSourceController extends Controller
{
    public function index()
    {
    	$list=JobSource::all();
    	return view("admin.source_job.index",get_defined_vars());
    }

    public function add()
    {
    	return view("admin.source_job.add");
    }

    public function edit(Request $req)
    {
    	$item=JobSource::find($req->id);
    	return view("admin.source_job.edit",get_defined_vars());
    }

    public function save(Request $req)
    {
    	$messages = [
            'name.required' => 'Enter source name'
        ];
        $rules = [
            'name' => ['required'],
        ];
        $this->validate($req,$rules,$messages);
    	if(isset($req->id))
    	{
			JobSource::where('id',$req->id)->update(['name'=>$req->name]);
    		
    	}else
    	{
    		JobSource::create(['name'=>$req->name]);
    	}
    	return redirect()->route('admin.source_job.list')->with("success","Saved successfully");
    }

    public function delete(Request $req)
    {
    	JobSource::find($req->id)->delete();
    	return back()->with("success","Deleted successfully");
    }
}
