@extends('layouts.admin')
@section('title','Add Work Category')
@section('nav-title', 'Add Work Category')
@section('content')
    <div class="container-fluid">
        <form method="post" action="{{route('admin.work_category.save')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Add Work Category</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="work_cateogry_block">
                                        <div class="shadow row" id="work_cat_row-1">
                                            <div class="col-md-6">
                                                <label>Name</label>
                                                <input type="text" value="" name="name" class="form-control" placeholder="Work Category" autocomplete="off">
                                                @if($errors->has('name'))
                                                    <div class="alert_danger mb-2">{{ $errors->first('name') }}</div>
                                                @endif
                                            </div>
                                        </div>
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
@section('js')
    <script>
            var limit = 1;
            $(document).on("click",".btn-add",function (e) 
            {
                // alert(limit);
                e.preventDefault();
                if (limit < 10) 
                {
                    var pointer = getLineItemsBlockLength();
                    var content = $('#work_cat_row-1').html();
                    $(".work_cateogry_block").append('<div class="shadow row" id="work_cat_row-'+(pointer+1)+'">'+content+'</div>');
                    $('#work_cat_row-'+(pointer+1)).find('.item-count').text(pointer+1);
                    $('#work_cat_row-'+(pointer+1)).find('.btn-del').removeClass('d-none');
                    limit++;
                    $(".hidden_item_count").val(limit);
                    if(limit == 10)
                    $(this).fadeOut();
                }
            });
    
            function refreshCounter()
            {
                var count = 1;
                $('.work_cateogry_block .shadow').each(function()
                {
                    $(this).find('.item-count').text(count);
                    count++;
                });
                if(limit == 10)
                    $('.btn-add').fadeIn();
                limit--;
                $(".hidden_item_count").val(limit);
            }
    
            function getLineItemsBlockLength()
            {
                return $(".work_cateogry_block .shadow").length;
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
    </script>
@endsection