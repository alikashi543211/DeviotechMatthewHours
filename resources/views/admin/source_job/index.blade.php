@extends('layouts.admin')
@section('title','Job Sources')
@section('nav-title', 'Job Sources')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 text-right">
                <a href="{{route('admin.source_job.add')}}" class="btn btn-success">+ Add Job Source</a>
            </div>
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title font-weight-bold"> Job Sources</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable table-bordered table-striped">
                            <thead class="text-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Source</th>
                                    <th>Added Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <a href="{{route('admin.source_job.edit',['id'=>$item->id] )}}" class="btn btn-success btn-sm">Edit</a>
                                        <a href="javascript:void(0);" data-href="{{route('admin.source_job.delete',['id'=>$item->id])}}" data-toggle="modal" data-target="#deleteModal" data-id="{{ $item->id }}" class="btn btn-sm btn-danger del">Delete</a>
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
