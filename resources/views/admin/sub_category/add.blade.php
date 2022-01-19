@extends('layouts.admin')
@section('title','Add SubCategory')
@section('nav-title', 'Add SubCategory')
@section('content')
    <div class="container-fluid">
        <form method="post" action="{{route('admin.sub_category.save')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Add SubCategory</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <lablel>Name</lablel>
                                    <div class="form-group">
                                        <input type="text" value="{{old('name')}}" name="name" class="form-control" placeholder="Name" autocomplete="off">
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
                                            <option value="{{$item->id}}" {{ (old('category_id') && old('category_id')==$item->id) ? 'selected' : '' }}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('category_id'))
                                        <div class="alert_danger mb-2">{{ $errors->first('category_id') }}</div>
                                    @endif
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