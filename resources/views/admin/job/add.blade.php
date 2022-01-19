@extends('layouts.admin')
@section('title','Add Job')
@section('nav-title', 'Add Job')
@section('content')
    <div class="container-fluid">
        <form method="post" action="" enctype="multipart/form-data" id="job_form">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Add Job</h4>
                        </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <lablel>Code</lablel>
                                                    <input type="text" value="{{old('name')}}" name="code" class="form-control" placeholder="Code" autocomplete="off">
                                                    @if($errors->has('name'))
                                                        <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <lablel>Client</lablel>
                                                    <select class="mb-0 form-control" name="client_id">
                                                        <option value="" selected="">Select Client</option>
                                                        @foreach($client_list as $item)
                                                            <option value="{{ $item->id }}">{{$item->name}}</option>
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
                                                    <textarea class="form-control" name="description" placeholder="Description"></textarea>
                                                    @if($errors->has('name'))
                                                        <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <lablel>Contact Person</lablel>
                                                    <input type="text" value="{{old('name')}}" name="contact_person" class="form-control" placeholder="Contact person" autocomplete="off">
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
                                                    <select class="mb-0 form-control main_cat" name="category_id">
                                                        <option value="" selected="">Select Category</option>
                                                        @foreach($category_list as $cat)
                                                            <option value="{{ $cat->id }}">{{$cat->name}}</option>
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
                                                    <select class="mb-0 form-control sub_cat" name="sub_category_id">
                                                        <option value="" selected="">Select Sub Category</option>
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
                                                    <input type="text" value="{{old('name')}}" name="job_value" class="form-control" autocomplete="off" placeholder="Job value">
                                                    @if($errors->has('name'))
                                                        <span class="invalid_error">{{ $errors->first('name') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <lablel>Status</lablel>
                                                    <select class="mb-0 form-control" name="job_status_id">
                                                        <option value="" selected="">Select Status</option>
                                                        @foreach($status_list as $item)
                                                            <option value="{{ $item->id }}">{{ ucfirst($item->name) }}</option>
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
                                                    <select class="mb-0 form-control" name="job_source_id">
                                                        <option value="" selected="">Select Source</option>
                                                        @foreach($source_list as $item)
                                                            <option value="{{ $item->id }}">{{ucfirst($item->name)}}</option>
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
                                                    <input type="text" value="{{old('name')}}" name="comment" class="form-control" autocomplete="off" placeholder="Job Comment">
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
            $(".form-control:visible").each(function () {
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