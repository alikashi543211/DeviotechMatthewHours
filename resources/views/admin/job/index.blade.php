@extends('layouts.admin')
@section('title','Jobs')
@section('nav-title', 'Jobs')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="clearfix mr-3">
                <div class="col-md-12">
                    <div class="dropdown pull-right">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ request()->status_text ? request()->status_text : 'All' }} Jobs
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item status_item" href="javascript:void();">All</a>
                            @foreach($status_list as $item)
                                <a class="dropdown-item status_item" data="{{$item->id}}" data-value="{{ucfirst($item->name)}}" href="javascript:void();">{{ ucfirst($item->name) }}</a>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{route('admin.job.add')}}" class="btn btn-success pull-right">+ Add Job</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title font-weight-bold"> Jobs</h4>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="filter-form">
                        <input type="hidden" name="job_status_id" id="job_status_id" class="filter_by" value="{{ request()->job_status_id }}">
                        <input type="hidden" name="status_text" id="status_text" class="filter_by" value="{{ request()->status_text }}">
                        <input type="hidden" name="filter" value="filter">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="text" name="from_date" class="form-control datepicker filter_by" autocomplete="off" readonly placeholder="From date" value="{{ request()->from_date }}" />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="text" name="to_date" class="form-control datepicker filter_by" autocomplete="off" readonly placeholder="To date" value="{{ request()->to_date }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Client</label>
                                            <select class="form-control client filter_by" name="client_id" id="client">
                                                <option selected value="">Select Client</option>
                                                @foreach($client_list as $item )
                                                    <option value="{{$item->id}}" @if(request()->client_id==$item->id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select class="form-control category filter_by" name="category_id" id="client">
                                                <option selected value="">Select Category</option>
                                                @foreach($category_list as $item)
                                                    <option value="{{$item->id}}" @if(request()->category_id==$item->id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-sm bold float-right export_table_btn" type="button">
                                    Export To Excel
                                </button>
                                <button class="btn btn-danger btn-sm bold float-right clear" type="button">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table datatable table-bordered table-striped listing_table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Job No</th>
                                    <th>Date Started</th>
                                    <th>Date Finished</th>
                                    <th>Job Status</th>
                                    <th>Job Code</th>
                                    <th>Client Code</th>
                                    <th>Client Name</th>
                                    <th>Description</th>
                                    <th>Contact Person</th>
                                    <th>Main Category</th>
                                    <th>Sub Category</th>
                                    <th>Job Comment</th>
                                    <th>Work Comment</th>
                                    <th>Job Value</th>
                                    <th>Source</th>
                                    <th>Total Hours Spent</th>
                                    <th>Revenue</th>
                                    <th>Revenue per Hour</th>
                                    <th>Added Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $item->end_date ?? '' }}</td>
                                        <td>{{ ucfirst($item->job_status->name) }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->client->code }}</td>
                                        <td>{{ $item->client->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->contact_person ?? '' }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>{{ $item->sub_category->name }}</td>
                                        <td>{{ $item->comment }}</td>
                                        <td>{{ $item->work_comment }}</td>
                                        <td>{{ $item->job_value }}</td>
                                        <td>{{ $item->job_source->name }}</td>
                                        <td>{{ hours_spent($item) }}</td>
                                        <td>{{ $item->job_value }}</td>
                                        <td>{{ revenue_per_hour($item) }}</td>
                                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            @if($item->job_status->name!="closed")
                                                <a href="{{route('admin.job.edit',['id'=>$item->id] )}}" class="btn btn-success btn-sm">Edit</a>
                                            @endif
                                            <a href="{{route('admin.job.tasks',['id'=>$item->id] )}}" class="btn btn-warning btn-sm">Booked Hours</a>
                                            <a href="javascript:void(0);" data-href="{{route('admin.job.delete',['id'=>$item->id])}}" data-toggle="modal" data-target="#deleteModal" data-id="{{ $item->id }}" class="btn btn-sm btn-danger del">Delete</a>
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
@section('js')
    <script>
        $('.clear').on('click',function(e){
            e.preventDefault();
            $('.filter_by').val('');
            $('.filter-form').submit();
        });

        $(document).on('change', '.filter_by',function(e){
            e.preventDefault();
            $('.filter-form').submit();
        });
        
        $(document).on('click','.status_item',function(e){
            e.preventDefault();
            var status_id=$(this).attr("data");
            var status_text=$(this).attr("data-value");
            $("#job_status_id").val(status_id);
            $("#status_text").val(status_text);
            $('.filter-form').submit();
        });
        
        // END EXPORT TO EXCEL
    </script>
@endsection
