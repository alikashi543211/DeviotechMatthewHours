<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $list=Category::all();
        return view("admin.category.index",get_defined_vars());
    }

    public function add()
    {
        return view("admin.category.add");
    }

    public function edit(Request $req)
    {
        $item=Category::find($req->id);
        return view("admin.category.edit",get_defined_vars());
    }

    public function save(Request $req)
    {
        $messages = [
            'name.required' => 'Enter category name'
        ];
        $rules = [
            'name' => ['required'],
        ];
        $this->validate($req,$rules,$messages);
        if(isset($req->id))
        {
            Category::where('id',$req->id)->update(['name'=>$req->name]);
            
        }else
        {
            Category::create(['name'=>$req->name]);
        }
        return redirect()->route('admin.category.list')->with("success","Saved successfully");
    }

    public function delete(Request $req)
    {
        Category::find($req->id)->delete();
        return back()->with("success","Deleted successfully");
    }
}
