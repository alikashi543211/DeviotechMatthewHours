@extends('layouts.admin')
@section('title','Closed Jobs')
@section('nav-title', 'Closed Jobs')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 text-right">
            </div>
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title font-weight-bold"> Closed Jobs</h4>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="filter-form">
                        <input type="hidden" name="filter" value="filter">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="text" name="from_date" class="form-control datepicker" autocomplete="off" readonly placeholder="From date" value="{{ request()->from_date }}" />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="text" name="to_date" class="form-control datepicker" autocomplete="off" readonly placeholder="To date" value="{{ request()->to_date }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 d-none">
                                        <div class="form-group">
                                            <label>Search by</label>
                                            <select class="form-control client" name="search_by" id="client">
                                                <option selected value="">Select Option</option>
                                                <option value="revenue">Revenue</option>
                                                <option value="category">Category</option>
                                                <option value="client">Client</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Client</label>
                                            <select class="form-control client" name="client_id" id="client">
                                                <option selected value="">Select Client</option>
                                                @foreach($client_list as $item )
                                                    <option value="{{$item->id}}" @if(request()->category_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select class="form-control category" name="category_id" id="client">
                                                <option selected value="">Select Category</option>
                                                @foreach($category_list as $item)
                                                    <option value="{{$item->id}}" @if(request()->category_id) selected @endif>{{$item->name}}</option>
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
                                <button class="btn btn-primary btn-sm bold float-left" type="submit">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table datatable table-bordered table-striped">
                            <thead class="text-primary">
                                <tr>
                                    <th>Date Started</th>
                                    <th>Date Finished</th>
                                    <th>Client code</th>
                                    <th>Client name</th>
                                    <th>Job no</th>
                                    <th>Job Main Cat</th>
                                    <th>Job Description</th>
                                    <th>Hours spent</th>
                                    <th>Revenue</th>
                                    <th>Revenue per Hour</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $item)
                                    @if(is_null($item->end_date))
                                        @continue
                                    @endif
                                    <tr>
                                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $item->end_date }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->client->name }}</td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ get_hours($item->hours_spent) }}</td>
                                        <td>{{ $item->job_value }}</td>
                                        <td>{{ $item->revenue_per_hour }}</td>
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
            $('.filter-form input:checkbox').prop('checked', false);
            $('.filter-form select').val('');
           $('.filter-form').submit();
        });
    </script>
@endsection
