<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
	<title>Crypterium</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta name="viewport" content="user-scalable=no, width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui" />

	<meta name="theme-color" content="#3F6EBF" />
	<meta name="msapplication-navbutton-color" content="#3F6EBF" />
	<meta name="apple-mobile-web-app-status-bar-style" content="#3F6EBF" />

	<!-- Favicons
		================================================== -->
	<link rel="shortcut icon" href="{{ asset('theme') }}/img/favicon.ico">
	<link rel="apple-touch-icon" href="{{ asset('theme') }}/img/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('theme') }}/img/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('theme') }}/img/apple-touch-icon-114x114.png">

	<!-- CSS
		================================================== -->
	<link rel="stylesheet" href="{{ asset('theme') }}/css/style.min.css" type="text/css">

	<!-- Load google font
		================================================== -->
	<script type="text/javascript">
		WebFontConfig = {
			google: {
				families: ['Open+Sans:300,400,500', 'Lato:900', 'Poppins:400', 'Catamaran:300,400,500,600,700']
			}
		};
		(function() {
			var wf = document.createElement('script');
			wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
				'://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
			wf.type = 'text/javascript';
			wf.async = 'true';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(wf, s);
		})();
	</script>

	<!-- Load other scripts
		================================================== -->
	<script type="text/javascript">
		var _html = document.documentElement,
			isTouch = (('ontouchstart' in _html) || (navigator.msMaxTouchPoints > 0) || (navigator.maxTouchPoints));

		_html.className = _html.className.replace("no-js", "js");

		isTouch ? _html.classList.add("touch") : _html.classList.add("no-touch");
	</script>
	<script type="text/javascript" src="{{ asset('theme') }}/js/device.min.js"></script>
</head>

<body>
	<!-- start main -->
	<section role="main">
		<!-- start section -->
		<section class="section section--no-pt section--no-pb section--light-bg">
			<div class="grid grid--container">
				<div class="authorization authorization--registration">
					<a class="site-logo" href="{{ route('home') }}">
						<img class="img-responsive" width="175" height="42" src="{{ asset('theme') }}/img/site_logo_2.png" alt="demo">
					</a>
					<form class="authorization__form" method="POST" action="{{route('register')}}">
                        @csrf
                        <h3 class="__title">Sign Up</h3>

                        <div class="input-wrp">
                            <input class="textfield @error('name') is-invalid @enderror" type="text" name="name" value="" placeholder="Name" />
                            @error('name')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
                        </div>

                        <div class="input-wrp">
                            <input class="textfield @error('email') is-invalid @enderror" type="text" value="" name="email" placeholder="Email" />
                            @error('email')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
                        </div>

                        <div class="input-wrp">
                            <i class="textfield-ico fontello-eye"></i>
                            <input class="textfield @error('password') is-invalid @enderror" type="password" name="password" value="" placeholder="Password" />
                            @error('password')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
                        </div>

                        <div class="input-wrp">
                            <i class="textfield-ico fontello-eye"></i>
                            <input class="textfield @error('password_confirmation') is-invalid @enderror" type="password" value="" name="password_confirmation" placeholder="Confirm Password" />
                            @error('password_confirmation')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
                        </div>
                        <p>
                            <button class="custom-btn custom-btn--medium custom-btn--style-2 wide" type="submit" role="button">Submit</button>
                        </p>
                        <br>
                        <p class="text--center"><a href="{{route('login')}}">Sign In</a> if you don’t have an account</p>
                    </form>
				</div>
			</div>
		</section>
		<!-- end section -->
	</section>
	<!-- end main -->

	<div id="btn-to-top-wrap">
		<a id="btn-to-top" class="circled" href="javascript:void(0);" data-visible-offset="800"></a>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script>
		window.jQuery || document.write('<script src="{{ asset('
			theme ') }}/js/jquery-2.2.4.min.js"><\/script>')
	</script>

	<script type="text/javascript" src="{{ asset('theme') }}/js/main.min.js"></script>
	<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
	<script>
		(function(b, o, i, l, e, r) {
			b.GoogleAnalyticsObject = l;
			b[l] || (b[l] =
				function() {
					(b[l].q = b[l].q || []).push(arguments)
				});
			b[l].l = +new Date;
			e = o.createElement(i);
			r = o.getElementsByTagName(i)[0];
			e.src = 'https://www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e, r)
		}(window, document, 'script', 'ga'));
		ga('create', 'UA-XXXXX-X', 'auto');
		ga('send', 'pageview');
	</script>

	<!-- BEGIN JIVOSITE CODE {literal} -->
	<script type='text/javascript'>
		(function() {
			var widget_id = 'Paw7lkpeN6';
			var d = document;
			var w = window;

			function l() {
				var s = document.createElement('script');
				s.type = 'text/javascript';
				s.async = true;
				s.src = '//code.jivosite.com/script/widget/' + widget_id;
				var ss = document.getElementsByTagName('script')[0];
				ss.parentNode.insertBefore(s, ss);
			}
			if (d.readyState == 'complete') {
				l();
			} else {
				if (w.attachEvent) {
					w.attachEvent('onload', l);
				} else {
					w.addEventListener('load', l, false);
				}
			}
		})();
	</script>
	<!-- {/literal} END JIVOSITE CODE -->
</body>

</html>
