@extends('layouts.admin')
@section('title','Edit Job')
@section('nav-title', 'Edit Job')
@section('content')
    <div class="container-fluid">
        <form method="post" action="" enctype="multipart/form-data" id="job_form">
            @csrf
            <input type="hidden" name="id" value="{{$job->id}}">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Edit Job</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lablel>Code</lablel>
                                                <input type="text" value="{{$job->code}}" name="code" class="form-control validate_field" placeholder="Code" autocomplete="off">
                                                @if($errors->has('name'))
                                                    <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lablel>Client</lablel>
                                                <select class="mb-0 form-control validate_field" name="client_id">
                                                    <option value="" selected="">Select Client</option>
                                                    @foreach($client_list as $item)
                                                        <option value="{{ $item->id }}" @if($job->client_id==$item->id) selected @endif>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('name'))
                                                    <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lablel>Description</lablel>
                                                <textarea class="form-control validate_field" name="description" placeholder="Description">{{$job->description}}</textarea>
                                                @if($errors->has('name'))
                                                    <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lablel>Contact Person</lablel>
                                                <input type="text" value="{{$job->contact_person}}" name="contact_person" class="form-control validate_field" placeholder="Contact person" autocomplete="off">
                                                @if($errors->has('name'))
                                                    <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lablel>Category</lablel>
                                                <select class="mb-0 form-control main_cat validate_field" name="category_id">
                                                    <option value="" selected="">Select Category</option>
                                                    @foreach($category_list as $cat)
                                                        <option value="{{ $cat->id }}" @if($job->category_id==$cat->id) selected @endif>{{$cat->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('name'))
                                                    <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 sub_cat_box">
                                            <div class="form-group">
                                                <lablel>Sub Category</lablel>
                                                <select class="mb-0 form-control sub_cat validate_field" name="sub_category_id">
                                                    @foreach($sub_cat_list as $item)
                                                        <option value="{{ $item->id }}" @if($job->sub_category_id==$item->id) selected @endif>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('name'))
                                                    <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lablel>Job Value</lablel>
                                                <input type="text" value="{{$job->job_value}}" name="job_value" class="form-control validate_field" autocomplete="off" placeholder="Job value">
                                                @if($errors->has('name'))
                                                    <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lablel>Status</lablel>
                                                <select class="mb-0 form-control validate_field" name="job_status_id">
                                                    <option value="" selected="">Select Status</option>
                                                    @foreach($status_list as $item)
                                                        <option value="{{ $item->id }}" @if($job->job_status_id==$item->id) selected @endif>{{ucfirst($item->name)}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('name'))
                                                    <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lablel>Source</lablel>
                                                <select class="mb-0 form-control validate_field" name="job_source_id">
                                                    <option value="" selected="">Select Source</option>
                                                    @foreach($source_list as $item)
                                                        <option value="{{ $item->id }}" @if($job->job_source_id==$item->id) selected @endif>{{ ucfirst($item->name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('name'))
                                                    <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lablel>Job Comment</lablel>
                                                <input type="text" value="{{$job->comment}}" name="comment" class="form-control" autocomplete="off" placeholder="Job Comment">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mt-3">
                                            <button type="button" class="btn btn-primary save_job_btn">Save</button>
                                            <a href="{{route('admin.dashboard')}}" class="btn btn-danger">Close</a>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('js')
    <script>
        // START SHOW SUB CATEGORIES ON CHANGE CATEGORY
        $(document).on("change", '.main_cat', function()
        {
            var cat_id=$(this).val();
            var option='<option value="" selected="">Select Sub Category</option>';
            $.ajax({
                type: "GET",
                url: "{{ route('admin.ajax.job.subcategory_list') }}?id="+cat_id,
                success: function (response) {
                    $(".sub_cat_box").removeClass("d-none");
                    $(".sub_cat").html(option+response);
                }
            });
        });
        // END SHOW SUB CATEGORIES ON CHANGE CATEGORY

        //START vALIDATE FUNCTION
        function validate() {
            var valid = true;
            $("form").find('.alert-warning').remove();
            $(".validate_field:visible").each(function () {
                if ($(this).val() == "") {
                    $(this).closest("div").find(".alert_danger").remove();
                    $(this)
                    .closest("div")
                    .append('<div class="alert_danger mb-2">This filed is required</div>');
                    valid = false;
                } else {
                    $(this).closest("div").find(".alert_danger").remove();
                }
            });
            if (!valid) {
                    $("html, body").animate(
                    {
                        scrollTop: $(".alert_danger:first").offset().top-80,
                    },
                    100
                );
            }
            return valid;
        }
        //END VALIDATION FUNCTION

        // START SAVE DATA OF JOB
        $(document).on("click", '.save_job_btn', function(){
            var form = $('#job_form').serialize();
            if(validate())
            {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.ajax.job.save') }}",
                    data: form,
                    success: function (response) {
                        // toastr.success(response);
                        window.location.href="{{route('admin.job.list')}}";
                    },
                });
            }
        });
        // END SAVE DATA OF JOB
    </script>
@endsection