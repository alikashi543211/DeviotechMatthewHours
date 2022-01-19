<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('admin_theme/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('admin_theme/admin_theme/img/favicon.png') }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title> @yield('title') | Surecandidate Hours </title>
        <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Quicksand:300,400,700|Material+Icons" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        <!-- CSS Files -->
        <link href="{{ asset('admin_theme/css/material-dashboard.css?v=2.1.2') }}" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <style type="text/css">
          .table thead th{
            border-top-width: 1px;
            background-color: #77767B !important;
            color: #fff !important;
            font-weight: bold !important;
          }
          .bmd-form-group .bmd-label-floating, .bmd-form-group .bmd-label-placeholder{
            left: 10px;
          }
          .form-control{
            border: 2px solid #eee;
            border-radius: 8px;
            padding: 4px 10px;
          }
          input.form-control, textarea.form-control{
            background-image: none !important;
          }
          .form-group label{
            font-weight: bold;
            text-transform: capitalize;
            margin-bottom: 3px;
          }
          .card-title{
            font-weight: bold;
          }
          .navbar .navbar-brand{
            font-weight: bold;
          }
          .dataTables_filter{
            text-align: right;
          }
          .dataTables_paginate .pagination{
            justify-content: right;
          }
          .collapsein{
            margin-top: 20px;
            padding-left: 1.5rem;
            margin-bottom: 0;
            list-style: none;
          }
          select {
              background-image:
                linear-gradient(45deg, transparent 50%, gray 50%),
                linear-gradient(135deg, gray 50%, transparent 50%),
                linear-gradient(to right, #ccc, #ccc) !important;
              background-position:
                calc(100% - 20px) calc(1em + 2px),
                calc(100% - 15px) calc(1em + 2px),
                calc(100% - 2.5em) 0.5em !important;
              background-size:
                5px 5px,
                5px 5px,
                1px 1.5em !important;
              background-repeat: no-repeat !important;
            }

            select:focus {
              background-image:
                linear-gradient(45deg, green 50%, transparent 50%),
                linear-gradient(135deg, transparent 50%, green 50%),
                linear-gradient(to right, #ccc, #ccc) !important;
              background-position:
                calc(100% - 15px) 1em,
                calc(100% - 20px) 1em,
                calc(100% - 2.5em) 0.5em !important;
              background-size:
                5px 5px,
                5px 5px,
                1px 1.5em !important;
              background-repeat: no-repeat !important;
              outline: 0 !important;
            }
            select:-moz-focusring {
              color: transparent !important;
              text-shadow: 0 0 0 #000 !important;
            }
            .bootstrap-select > .dropdown-toggle,
            .bootstrap-select > .dropdown-toggle:hover,
            .bootstrap-select > .dropdown-toggle:active,
            .bootstrap-select > .dropdown-toggle:focus{
              padding: 7px 12px !important;
              margin: 0px !important;
              color: #0f0f0f !important;
              background: #fff !important;
              box-shadow: none !important;
              border: 2px solid #eee !important;
              border-radius: 8px !important;
            }
            .job-checks .form-check{
              padding-left: 18px;
              flex: 1;
            }
            .nav-tabs .nav-link.disabled{
              opacity: .6;
              cursor: not-allowed;
            }
            .bold{
              font-weight: bold !important;
            }
            .form-group .alert-danger{
              font-size: 12px;
              padding: 3px;
            }
          .modal-content .bg-info  {
              background: linear-gradient(60deg, #ab47bc, #8e24aa);
            }
            input[type="file"]{
                z-index: 100 !important;
                opacity: 1 !important;
                position: relative !important;
            }
            .card-header-primary {
                background: linear-gradient(60deg, #203885, #203885) !important;
            }
            .nav-tabs .nav-item .nav-link.active{
                background-color: rgb(231 65 51) !important;
                color:#fff !important;
            }
            .modal-overlay{
              position: fixed;
              height: 100%;
              width: 100%;
              top: 0;
              left: 0;
              background: rgba(255,255,255,.8);
              display: table;
              z-index: -1;
              visibility: hidden;
              opacity: 0;
              padding-top: 4%;
            }
            .modal-overlay.show{
              visibility: visible;
              opacity: 1;
              z-index: 9999;
            }
            label{
                color: #000000 !important;
            }
            .invalid_error
            {
                color:red;
                font-size:14px;
                font-weight: bold;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button:hover,
            .dataTables_wrapper .dataTables_paginate .paginate_button:active
            {
                background:white !important;
                border-color:white !important;
                /*padding:0px !important;
                margin:0px !important;*/
            }
            .sidebar[data-color="purple"] li.active>a 
            {
                background-color: #203885 !important;
            }
            .alert_danger
            {
                color: white;
                background-color: #f16161;
                border-color: #f5c6cb;
                padding: 2px;
                padding-left: 5px;
                border-radius: 2px;
            }
            .btn.btn-primary 
            {
              color: #fff;
              background-color: #203885;
              border-color: #203885;
            }
            .btn.btn-primary:focus, .btn.btn-primary:active, .btn.btn-primary:hover {
                  box-shadow: 0 14px 26px -12px rgb(93 110 163 / 5%), 0 4px 23px 0px rgb(32 56 133 / 66%), 0 8px 10px -5px rgb(32 56 133 / 58%);
                }
            }
            .btn.btn-primary:focus, .btn.btn-primary.focus, .btn.btn-primary:hover {
                color: #fff;
                background-color: #203885;
                border-color: #203885;
            }
            .btn.btn-primary:active:hover, .btn.btn-primary:active:focus, .btn.btn-primary:active.focus, .btn.btn-primary.active:hover, .btn.btn-primary.active:focus, .btn.btn-primary.active.focus, .open>.btn.btn-primary.dropdown-toggle:hover, .open>.btn.btn-primary.dropdown-toggle:focus, .open>.btn.btn-primary.dropdown-toggle.focus, .show>.btn.btn-primary.dropdown-toggle:hover, .show>.btn.btn-primary.dropdown-toggle:focus, .show>.btn.btn-primary.dropdown-toggle.focus
            {
              background-color: #203885;
              border-color: #203885;
            }
            .btn.btn-primary:focus, .btn.btn-primary.focus, .btn.btn-primary:hover {
                color: #fff;
                background-color: #203885;
                border-color: #203885;
            }
            .dropdown-menu .dropdown-item:hover, .dropdown-menu .dropdown-item:focus, .dropdown-menu a:hover, .dropdown-menu a:focus, .dropdown-menu a:active {
                box-shadow: 0 4px 20px 0px rgb(161 170 196), 0 7px 10px -5px rgb(162 174 213);
                background-color: #203885;
                color: #FFFFFF;
            }
        </style>
        @yield('css')
    </head>

    <body class="">
        <div class="wrapper">
            <div class="sidebar" data-color="purple" data-background-color="white" data-image="{{ asset('admin_theme/img/sidebar-1.jpg') }}">
                @include('admin.components.sidebar')
            </div>
            <div class="main-panel">
                @include('admin.components.navbar')
                <div class="content">
                    @yield('content')
                </div>
            </div>
        </div>
        <!-- Delete Modal -->
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
                            <a href="" class="btn btn-primary del_yes">Yes</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--   Core JS Files   -->
        <script src="{{ asset('admin_theme/js/core/jquery.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/core/popper.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/core/bootstrap-material-design.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/moment.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/sweetalert2.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/bootstrap-selectpicker.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/bootstrap-datetimepicker.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/bootstrap-tagsinput.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/jasny-bootstrap.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/nouislider.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
        <script src="{{ asset('admin_theme/js/plugins/chartist.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/bootstrap-notify.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="{{ asset('admin_theme/js/material-dashboard.js?v=2.1.2') }}" type="text/javascript"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <!-- Bootstrap Export To Excel -->
        <script src="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1569818907/jquery.table2excel.min.js"></script>
        <script>
            $(document).ready(function(){
                $('table.datatable').DataTable({
                    ordering: false,
                    pageLength: 10
                });
            });
        </script>
        @if(Session::has('success'))
            <script>
                toastr.success("{{ Session::get('success') }}");
            </script>
        @endif
        @if(Session::has('error'))
            <script>
                toastr.error("{{ Session::get('error') }}");
            </script>
        @endif
        <script>
            $(document).ready(function () {
                $(".del").click(function (e) {
                    e.preventDefault();
                    var target=$(this).attr("data-href");
                    $('.del_yes').attr('href',target);
                });
            });
            $( function() {
                $( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
            });
            // START EXPORT TO EXCEL
            $(function() {
                $(".export_table_btn").click(function(e){
                    var table = $(".listing_table");
                    if(table && table.length){
                        $(table).table2excel({
                            exclude: ".noExl",
                            name: "Excel Document Name",
                            filename: "job-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                            fileext: ".xls",
                            exclude_img: true,
                            exclude_links: true,
                            exclude_inputs: true,
                            preserveColors: false
                        });
                    }
                });
            });
        </script>
        @yield('js')
    </body>
</html>
