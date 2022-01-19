
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Surecandidate Hours - @yield('title')</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://forms.paperlessworkflows.ie/Hunt-Museum-PO/assets/css/bootstrap.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://forms.paperlessworkflows.ie/Hunt-Museum-PO/assets/css/style.css"/>
    <link href="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" />
    

<style type="text/css">
    body{
        font-family: 'Quicksand';
    }
    #toastr{
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        padding: 10px 30px;
        border-radius: 8px;
        display: none;
        margin-top: 10px;
        margin-bottom: 25px;
    }
    #toastr.success{
        background-color: rgba(40,167,69, .8);
    }
    #toastr.error{
        background-color: rgba(220,53,69, .8);
    }
    #price-per-acre{
        display: none;
    }
    .error{
        padding: 2px 5px;font-size: 12px;
        margin-top: -22px;
        margin-bottom: 10px;
    }
    .loader-overlay{
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        background: rgba(255,255,255,.8);
        display: table;
        z-index: -1;
        text-align: center;
        visibility: hidden;
        opacity: 0;
        padding-top: 20%;
    }
    .loader-text{
        display: table-cell;
        vertical-align: top;
        font-size: 20px;
        font-style: italic;
        color: #00B7B8;
        font-weight: bold;
    }
    .loader-overlay.show{
        visibility: visible;
        opacity: 1;
        z-index: 1;
    }
    .mt-2{
        margin-top: 2%;
    }
    .mt-2{
        margin-bottom: 2%;
    }
    label{
        font-weight: bold;
        font-size: 0.8rem !important;
    }
    .form{
        box-shadow: 0px 0px 10px #08413c;
		border-radius: 20px;
    }
    .form input[type="email"], .form input[type="password"], .form input[type="text"], .form select, .form textarea{
        height: 38px;
    }

    .form select option{
    	border-color: none;
    }
    .tshirt-single{
        width: 100%;
        float: left;
        display: flex;
    }
    .hidden{
        display: none;
    }
    .show-tshirts, .remove-tshirts{
        position: absolute;
        right: 14px;
        margin-top: -30px;
        font-size: 10px;
        font-weight: bold;
    }
    .bg-gray{
        background: #eee;
    }
    .table td, .table th {
        padding: .55rem;
        font-size: 14px;
    }
    .error{
        margin-top: 0px;
    }
    .dateTime input{
    	width: 100%;
    	color: #6c757d;
    	margin-bottom: 20px;
    	font-size: small;
    	height: 38px;
    	border: 1px solid #E2E5ED;
    	padding: 0px 40px 0px 16px;
    	background-color: white;
    	border-radius: 4px;
    }
    label.error{
        padding: 2px 5px;
        font-size: 11px !important;
        margin-top: -24px;
        color: red !important;
    }
    #submit-form{  
    	margin-bottom: 25px;
    	float: right; 
    }
    .remove-item {
        position: absolute;
        right: 30px;
        top: 24px;
    }
    .bootstrap-timepicker-meridian, .meridian-column
    {
        display: none;
    }
    .header{
        background: white;
    }
    .invalid_error
    {
        color:red;
        font-size:14px;
    }
    .header_front
    {
        margin-top:60px !important;
        margin-bottom:60px !important;
    }
    .form_group{
        margin-top: 10px;
    }
    @media(max-width: 768px)
    {
        .header_img{
            width:100%;
        }
    }

</style>
@yield('css')
</head>
<body>

    @include("front.components.header")

    @yield("content")

    @include("front.components.footer")

<!-- js -->
<script src="https://forms.paperlessworkflows.ie/Hunt-Museum-PO/assets/js/jquery-3.4.1.min.js"></script>
<script src="https://forms.paperlessworkflows.ie/Hunt-Museum-PO/assets/js/bootstrap.min.js"></script>
<script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>

<script type="text/javascript">
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @elseif(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
    $( function() {
        $( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
    });
</script>
@yield('js')
</body>
</html>
