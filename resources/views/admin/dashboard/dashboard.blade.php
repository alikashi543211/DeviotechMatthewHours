@extends('layouts.admin')
@section('title','Dashboard')
@section('nav-title', 'Dashboard')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">supervisor_account</i>
                    </div>
                    <p class="card-category">Users</p>
                    <h3 class="card-title">{{ $user_count }}</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        Total number of users
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">work</i>
                    </div>
                    <p class="card-category">Jobs</p>
                    <h3 class="card-title">{{ $job_count }}</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        Total number of jobs
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">add_task</i>
                    </div>
                    <p class="card-category">Work Categories</p>
                    <h3 class="card-title">{{ $work_category_count }}</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        Total number fo work categories
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">groups</i>
                    </div>
                    <p class="card-category">Total Client</p>
                    <h3 class="card-title">{{ $client_count }}</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        Total number of clients
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title font-weight-bold">Recent</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable table-bordered table-striped">
                            <thead class="text-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Picture</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if ($item->image == "")
                                            <img src="{{ asset('admin_theme/img/faces/marc.jpg') }}" class="img-fluid rounded-circle" width="30" alt="DP">
                                        @else
                                            <img src="{{ asset($item->image) }}" class="img-fluid rounded-circle" width="30" alt="DP">
                                        @endif

                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#deleteModal" data-id="{{ $item->id }}" class="btn btn-sm btn-danger del">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post">
                @csrf
                <div class="modal-header bg-danger">
                    <h5 class="modal-title font-weight-bold text-white" id="exampleModalLabel">Are you sure?</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" class="id" value="">
                    <h4 class="font-weight-bold">You want to delete it permanently!</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $(".del").click(function (e) {
                e.preventDefault();
                $('.id').val($(this).attr('data-id'));
            });
        });
    </script>
@endsection
