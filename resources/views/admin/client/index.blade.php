@extends('layouts.admin')
@section('title','Clients')
@section('nav-title', 'Clients')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 text-right">
                <a href="{{route('admin.client.add')}}" class="btn btn-success">+ Add Client</a>
            </div>
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title font-weight-bold"> Clients</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable table-bordered table-striped">
                            <thead class="text-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Added Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <a href="{{route('admin.client.edit',['id'=>$item->id] )}}" class="btn btn-success btn-sm">Edit</a>
                                        <a href="javascript:void(0);" data-href="{{route('admin.client.delete',['id'=>$item->id])}}" data-toggle="modal" data-target="#deleteModal" data-id="{{ $item->id }}" class="btn btn-sm btn-danger del">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
