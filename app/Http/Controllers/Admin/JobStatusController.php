<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobStatus;

class JobStatusController extends Controller
{
    public function index()
    {
    	$list=JobStatus::all();
    	return view("admin.status_job.index",get_defined_vars());
    }

    public function add()
    {
    	return view("admin.status_job.add");
    }

    public function edit(Request $req)
    {
    	$item=JobStatus::find($req->id);
    	return view("admin.status_job.edit",get_defined_vars());
    }

    public function save(Request $req)
    {
    	$messages = [
            'name.required' => 'Enter status name'
        ];
        $rules = [
            'name' => ['required'],
        ];
        $this->validate($req,$rules,$messages);
    	if(isset($req->id))
    	{
			JobStatus::where('id',$req->id)->update(['name'=>$req->name]);
    		
    	}else
    	{
    		JobStatus::create(['name'=>$req->name]);
    	}
    	return redirect()->route('admin.status_job.list')->with("success","Saved successfully");
    }

    public function delete(Request $req)
    {
    	JobStatus::find($req->id)->delete();
    	return back()->with("success","Deleted successfully");
    }
}
