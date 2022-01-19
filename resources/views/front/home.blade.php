@extends("layouts.front")
@section('title','Registration Form')
@section('css')
   <style>
       .add_other:hover,.add_other:active,.add_other:focus
       {
            color:white;
            border-color:#007bff !important;
       }
       .alert-danger
       {
            color: white;
            background-color: #f16161;
            border-color: #f5c6cb;
            padding: 2px;
            padding-left: 5px;
            border-radius: 2px;
       }
       .form
        {
            box-shadow: 0px 0px 30px #08413c;
            margin-top:0px !important;
        }
   </style>
@endsection
@section('content')
    <div class="container">
        <div class="form">
            <form action="{{route('save_job')}}" method="post" class="accordion job_form" id="job_form">
                <input type="hidden" name="job_array_id[]" class="job_array_id">
                @csrf
                <div class="card">
                    <div class="card-header" id="property-info">
                        <h2>General Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Name *</label>
                                <input type="hidden" name="recruiter_id" value="{{ $user_id }}">
                                <input type="text" name="name" placeholder="Enter Name" required="" value="{{ $user->name }}" readonly  />
                            </div>
                            <div class="col-md-6">
                                <label>Date *</label>
                                <input type="text" name="booked_date" class="datepicker" readonly autocomplete="off" value="{{ date('d/m/Y') }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card job_card-1">
                    <div class="card-header">
                        <h2>Job Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="job_block">
                                    <div class="row shadow my-3" id="job_row-1">
                                        <div class="col-md-12">
                                            <h3 class="font-weight-bold pull-left">Job - <span class="item-count">1</span></h3>
                                            <input type="hidden" name="item_count" class="item_count" value="1">
                                            <button class="btn btn-danger btn-sm btn-del add_other my-4 d-none pull-right"><i class="fa fa-times"></i></button>
                                        </div>
                                        <div class="col-md-6 form_group">
                                            <label>Client / Job</label>
                                            <select class="job_field mb-0 client_job" name="job_id[]">
                                                <option value="" selected="">Select Client / Job</option>
                                                @foreach($job_list as $item)
                                                    <option value="{{$item->id}}">{{ $item->client->name}} - {{ $item->code }} - {{ $item->description }}</option>
                                                @endforeach
                                            </select> 
                                        </div>
                                        <div class="col-md-6 form_group">
                                            <label>Type Of Work</label>
                                            <select class="job_field mb-0" name="work_category_id[]">
                                                <option value="" selected="">Select type of work</option>
                                                @foreach($work_list as $item)
                                                    <option value="{{$item->id}}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 form_group mb-4">
                                            <label>Pick Hours on Job</label>
                                            <input type="text" name="job_hours[]" placeholder="Pick Hours" required="" class="time job_field mb-0" autocomplete="off" readonly />
                                        </div>
                                        <div class="col-md-6 form_group">
                                            <label>Comment on work</label>
                                            <textarea class="mb-0" name="work_comment[]" placeholder="Enter Comment" style="padding: 7.5px 10px;" /></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary btn-sm btn-add add_other my-2 pull-right"><i class="fa fa-plus"></i> Add Another Job</button>
                                </div>
                            </div>
                            <div class="col-md-12 my-3">
                                <button type="button" class="btn btn-success save_job_btn text-white">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var choices = ["00","05","10","15","20","25","30","35","40","45","50","55"]; //Time Interval Array
        // START ADD MORE
        var limit = 1;
        $(document).on("click",".btn-add",function (e) 
        {
            // alert(limit);
            e.preventDefault();
            if (limit < 10) 
            {
                var pointer = getLineItemsBlockLength();
                var content = $('#job_row-1').html();
                $(".job_block").append('<div class="shadow row my-3" id="job_row-'+(pointer+1)+'">'+content+'</div>');
                $('#job_row-'+(pointer+1)).find('.item-count').text(pointer+1);
                $(".item_count").val(pointer+1);
                $('#job_row-'+(pointer+1)).find('.btn-del').removeClass('d-none');
                $('#job_row-'+(pointer+1)).find('.time').clockpicker(
                {
                    placement: 'bottom',
                    align: 'left',
                    autoclose: true,
                    default: 'now',
                    donetext: "Select",
                    afterShow: function() 
                    {
                        $(".clockpicker-minutes").find(".clockpicker-tick").filter(function(index,element)
                        {
                            return !($.inArray($(element).text(), choices)!=-1)
                        }).remove();
                    }
                });
                limit++;
                if(limit == 10)
                $(this).fadeOut();
            }
        });

        function refreshCounter()
        {
            var count = 1;
            $('.job_block .shadow').each(function()
            {
                $(this).find('.item-count').text(count);
                count++;
            });
            if(limit == 10)
                $('.btn-add').fadeIn();
            limit--;
            $(".item_count").val(limit);
        }

        function getLineItemsBlockLength()
        {

            return $(".job_block .shadow").length;
        }

        $(document).on('click', '.btn-del', function(){
            $(this).closest('.shadow').remove();
            refreshCounter();
        });

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });
        // END ADD MORE

        // START VALIDATION
        function validate() {
            var valid = true;
            $("form").find('.alert-warning').remove();
            $(".job_field:visible").each(function () {
                if ($(this).val() == "") {
                $(this).closest("div").find(".alert-danger").remove();
                $(this)
                  .closest("div")
                  .append('<div class="alert-danger mb-2">This filed is required</div>');
                valid = false;
                } else {
                    $(this).closest("div").find(".alert-danger").remove();
                }
            });
            if (!valid) {
                $("html, body").animate(
                {
                    scrollTop: $(".alert-danger:first").offset().top-80,
                },
                100
                );
            }
            return valid;
        }
        // END VALIDATION


        // START CLOCKPICKER
        $(".time").clockpicker(
        {
            placement: 'bottom',
            align: 'left',
            autoclose: true,
            default: 'now',
            donetext: "Select",
        });
        // END CLOCKPICKER

        // START TIME CONVERTER
        function time_converter()
        {
            var T=["0.00", "0.02", "0.03", "0.05", "0.07", "0.08", "0.1", "0.12", "0.13", "0.15", "0.17", "0.18", "0.2", "0.22", "0.23", "0.25", "0.27", "0.28", "0.3", "0.32", "0.33", "0.35", "0.37", "0.38", "0.4", "0.42", "0.43", "0.45", "0.47", "0.48", "0.5", "0.52", "0.53", "0.55", "0.57", "0.58", "0.6", "0.62", "0.63", "0.65", "0.67", "0.68", "0.7", "0.72", "0.73", "0.75", "0.77", "0.78", "0.8", "0.82", "0.83", "0.85", "0.87", "0.88", "0.9", "0.92", "0.93", "0.95", "0.97", "0.98"];
            var time;
            $('.time').each(function(){
            if($(this).val() !== ''){
            time = $(this).val().split(':')
            $(this).val(parseInt(time[0])+parseFloat(T[parseInt(time[1])]));
            }
            });
        }
        // END TIME CONVERTER

        // START Save User Job
        $(document).on("click", '.save_job_btn', function(){ 
            if(validate())
            {
                time_converter();
                var form = $('#job_form').serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_job') }}",
                    data: form,
                    success: function (response) {
                        setTimeout(function(){
                            window.location.reload(true);
                        },1000);
                    },
                });
            }
        });
        // END Save User Job

    </script>
@endsection