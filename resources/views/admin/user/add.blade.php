@extends('layouts.admin')
@section('title','Add User')
@section('nav-title', 'Add User')
@section('content')
    <div class="container-fluid">
        <form method="post" action="{{route('admin.user.save')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Add User</h4>
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