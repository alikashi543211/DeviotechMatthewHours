@extends('layouts.admin')
@section('title','Edit SubCategory')
@section('nav-title', 'Edit SubCategory')
@section('content')
    <div class="container-fluid">
        <form method="post" action="{{route('admin.sub_category.save')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$sub_cat->id}}">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Edit SubCategory</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <lablel>Name</lablel>
                                    <div class="form-group">
                                        <input type="text" value="{{$sub_cat->name}}" name="name" class="form-control" placeholder="Name" autocomplete="off">
                                        @if($errors->has('name'))
                                            <div class="alert_danger mb-2">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label>Category</label>
                                    <select class="form-control client filter_by" name="category_id" id="client">
                                        <option selected value="">Select Category</option>
                                        @foreach($cat_list as $item )
                                            <option value="{{$item->id}}" @if($sub_cat->category_id==$item->id) selected @endif>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{route('admin.dashboard')}}" class="btn btn-danger">Close</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection