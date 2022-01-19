<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
    	$list=Client::all();
    	return view("admin.client.index",get_defined_vars());
    }

    public function add()
    {
    	return view("admin.client.add");
    }

    public function edit(Request $req)
    {
    	$item=Client::find($req->id);
    	return view("admin.client.edit",get_defined_vars());
    }

    public function save(Request $req)
    {
    	$messages = [
            'name.required' => 'Enter client name',
            'code.required' => 'Enter client code'
        ];
        $rules = [
            'name' => ['required'],
            'code'=> ['required']
        ];
        $this->validate($req,$rules,$messages);
    	if(isset($req->id))
    	{
			Client::where('id',$req->id)->update(['name'=>$req->name,
													'code'=>$req->code]);
    		
    	}else
    	{
    		Client::create(['name'=>$req->name,
							'code'=>$req->code]);
    	}
    	return redirect()->route('admin.client.list')->with("success","Saved successfully");
    }

    public function delete(Request $req)
    {
    	Client::find($req->id)->delete();
    	return back()->with("success","Deleted successfully");
    }
}
