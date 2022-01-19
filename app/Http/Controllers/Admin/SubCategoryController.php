<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;

class SubCategoryController extends Controller
{
    public function index()
    {
        $list=SubCategory::all();
        return view("admin.sub_category.index",get_defined_vars());
    }

    public function add()
    {
    	$cat_list=Category::all();
        return view("admin.sub_category.add",get_defined_vars());
    }

    public function edit(Request $req)
    {
        $sub_cat=SubCategory::find($req->id);
        $cat_list=Category::all();
        return view("admin.sub_category.edit",get_defined_vars());
    }

    public function save(Request $req)
    {
        $messages = [
            'name.required' => 'Enter subcategory name',
            'category_id.required' => 'Select main catgeory',
        ];
        $rules = [
            'name' => ['required'],
            'category_id' => ['required'],
        ];
        $this->validate($req,$rules,$messages);
        if(isset($req->id))
        {
            SubCategory::where('id',$req->id)->update(['name'=>$req->name,
        		'category_id'=>$req->category_id]);
            
        }else
        {
            SubCategory::create(['name'=>$req->name,
        		'category_id'=>$req->category_id]);
        }
        return redirect()->route('admin.sub_category.list')->with("success","Saved successfully");
    }

    public function delete(Request $req)
    {
        SubCategory::find($req->id)->delete();
        return back()->with("success","Deleted successfully");
    }
}
