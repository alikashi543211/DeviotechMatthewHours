<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('admin_theme/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('admin_theme/admin_theme/img/favicon.png') }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title> Login | Methew Hrs </title>
        <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Quicksand:300,400,700|Material+Icons" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        <!-- CSS Files -->
        <link href="{{ asset('admin_theme/css/material-dashboard.css?v=2.1.2') }}" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
         <style type="text/css">
    .table thead th{
      border-top-width: 1px;
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
        color: #0f0f0f;
        background: #fff;
        box-shadow: none;
        border: 2px solid #eee;
        border-radius: 8px;
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
      .card .card-header-warning .card-icon, .card .card-header-warning .card-text, .card .card-header-warning:not(.card-header-icon):not(.card-header-text), .card.bg-warning, .card.card-rotate.bg-warning .front, .card.card-rotate.bg-warning .back {
    		background: linear-gradient(60deg, #f16822, #f16822) !important;
		}
		.card .card-header-warning .card-icon, .card .card-header-warning:not(.card-header-icon):not(.card-header-text), .card .card-header-warning .card-text {
		    box-shadow: 0 4px 5px 0px rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgb(40 88 62) !important;
		}
		.btn.btn-primary:focus, .btn.btn-primary.focus, .btn.btn-primary:hover {
		    color: #fff;
		    background-color: #193282;
		    border-color: #193282;
		}
		.btn.btn-primary:focus, .btn.btn-primary:active, .btn.btn-primary:hover {
		    box-shadow: 0 14px 26px -12px #193282, 0 4px 23px 0px #193282, 0 8px 10px -5px #193282;
		}
		.btn-primary
		{
			background-color: #193282 !important;
		}
    .login_row
    {
      height: 100vh;
      align-content: center;
    }
    .btn.btn-primary:active:hover, .btn.btn-primary:active:focus, .btn.btn-primary:active.focus, .btn.btn-primary.active:hover, .btn.btn-primary.active:focus, .btn.btn-primary.active.focus, .open>.btn.btn-primary.dropdown-toggle:hover, .open>.btn.btn-primary.dropdown-toggle:focus, .open>.btn.btn-primary.dropdown-toggle.focus, .show>.btn.btn-primary.dropdown-toggle:hover, .show>.btn.btn-primary.dropdown-toggle:focus, .show>.btn.btn-primary.dropdown-toggle.focus
    {
      border-color:#1a3282 !important;
    }
  	</style>
        @yield('css')
    </head>
    <body>
        <div class="wrapper">
            <div class="content">
            	<div class="container-fluid">
		          <div class="row login_row">
		            <div class="col-md-4"></div>
		            <div class="col-md-4">
		              
	                <div class="text-center"><img src="{{ asset('images') }}/logo.png" class="img-fluid" width="200"></div>
		              <div class="card">
		                <div class="card-header card-header-warning">

		                    <h4 class="card-title">Enter your Login Details</h4>
		                </div>
		                <div class="card-body">
		                  <form method="post" action="{{route('login')}}">
                      @csrf                   
                        <div class="form-group">
		                      <label>Enter Email</label>
		                      <input type="email" name="email" class="form-control" required="">
                        </div>
		                    <div class="form-group">
		                      <label>Enter Password</label>
		                      <input type="password" name="password" class="form-control" required="">
                        </div>
		                    <button class="btn btn-primary btn-block bold">Sign In</button>
		                  </form>
		                </div>
		              </div>
		            </div>
		            <div class="col-md-4"></div>
		          </div>
		        </div>
	        </div>
	    </div>
        <!--   Core JS Files   -->
        <script src="{{ asset('admin_theme/js/core/jquery.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/core/popper.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/core/bootstrap-material-design.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/moment.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('admin_theme/js/plugins/bootstrap-selectpicker.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="{{ asset('admin_theme/js/material-dashboard.js?v=2.1.2') }}" type="text/javascript"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </body>
</html>