@extends('layouts.admin')
@section('title','Booked Hours')
@section('nav-title', 'Booked Hours')

@section('css')

<style>
    .fwb
    {
        font-weight: bold !important;
    }
</style>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="clearfix mr-3">
                <div class="col-md-12">
                    <div class="dropdown pull-right">
                        <button class="btn btn-primary btn-sm bold float-right export_table_btn" type="button">
                            Export To Excel
                        </button>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title font-weight-bold"> Booked Hours</h4>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="filter-form">
                        <input type="hidden" name="id" value="{{ request()->id }}">
                        <input type="hidden" name="status_text" id="status_text" class="filter_by" value="{{ request()->status_text }}">
                        <input type="hidden" name="filter" value="filter">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <input type="text" name="from_date" class="form-control datepicker filter_by" autocomplete="off" readonly placeholder="From date" value="{{ request()->from_date }}" />
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <input type="text" name="to_date" class="form-control datepicker filter_by" autocomplete="off" readonly placeholder="To date" value="{{ request()->to_date }}" />
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <select class="form-control client filter_by" name="recruiter_id" id="client">
                                                <option selected value="">Select Recruiter</option>
                                                @foreach($recruiter_list as $item )
                                                    <option value="{{$item->id}}" @if(request()->recruiter_id==$item->id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
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
                                    <th>Date Booked</th>
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
                                    <th>Booked By</th>
                                    <th>Source</th>
                                    <th>Work Category</th>
                                    <th>Hours Spent</th>
                                    <th>Added Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Job Task List Here.. -->
                                @foreach ($list as $item)
                                    <tr>
                                        <td>{{ $item->job->id }}</td>
                                        <td>{{ $item->job->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $item->booked_date }}</td>
                                        <td>{{ ucfirst($item->job->job_status->name) }}</td>
                                        <td>{{ $item->job->code }}</td>
                                        <td>{{ $item->job->client->code }}</td>
                                        <td>{{ $item->job->client->name }}</td>
                                        <td>{{ $item->job->description }}</td>
                                        <td>{{ $item->job->contact_person ?? '' }}</td>
                                        <td>{{ $item->job->category->name }}</td>
                                        <td>{{ $item->job->sub_category->name }}</td>
                                        <td>{{ $item->job->comment }}</td>
                                        <td>{{ $item->work_comment }}</td>
                                        <td>{{ $item->recruiter->name }}</td>
                                        <td>{{ $item->job->job_source->name }}</td>
                                        <td>{{ $item->work_category->name }}</td>
                                        <td>{{ $item->hours_spent }}</td>
                                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    @for($i=0;$i<=14;$i++)
                                        <td></td>
                                    @endfor
                                    <td class="fwb">Total</td>
                                    <td class="fwb">{{ $list->sum('hours_spent') }}</td>
                                    <td></td>
                                </tr>
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
        $(document).on('change','.filter_by',function(e){
            e.preventDefault();
            $('.filter-form').submit();
        });
    </script>

@endsection
