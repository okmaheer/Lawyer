<!doctype html>
<!--[if lt IE 7]>		<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>			<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>			<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->	<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}"> <!--<![endif]-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{Helper::getTextDirection()}}">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	@if (trim($__env->yieldContent('title')))
		<title>@yield('title')</title>
	@else 
		<title>{{ config('app.name') }}</title>
	@endif
	<meta name="description" content="@yield('description')">
	<meta name="keywords" content="@yield('keywords')">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<link rel="icon" href="{{ asset(Helper::getGeneralSettings('site_favicon')) }}" type="image/x-icon">
	@stack('PackageStyle')
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/normalize.css') }}" rel="stylesheet">
	<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/fontawesome/fontawesome-all.css') }}" rel="stylesheet">
	<link href="{{ asset('css/linearicons.css') }}" rel="stylesheet">
	<link href="{{ asset('css/themify-icons.css') }}" rel="stylesheet">
	<link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
	<link href="{{ asset('css/main.css') }}" rel="stylesheet">
	@if (Helper::getTextDirection() == 'rtl')
		<link href="{{ asset('css/rtl.css') }}" rel="stylesheet">
	@endif
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
	<link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
	<link href="{{ asset('css/transitions.css') }}" rel="stylesheet">
	@stack('stylesheets')
	@stack('inlineStyle')
	@php echo \App\Typo::setSiteStyling(); @endphp
    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!}
        var Map_key = {!! json_encode(Helper::getGoogleMapApiKey()) !!}
	</script>
	@if (Auth::user())
		<script type="text/javascript">
			var USERID = {!! json_encode(Auth::user()->id) !!}
			window.Laravel = {!! json_encode([
			'csrfToken'=> csrf_token(),
			'user'=> [
				'authenticated' => auth()->check(),
				'id' => auth()->check() ? auth()->user()->id : null,
				'name' => auth()->check() ? auth()->user()->first_name : null,
				'image' => !empty(auth()->user()->profile->avatar) ? asset('uploads/users/'.auth()->user()->id .'/'.auth()->user()->profile->avatar) : asset('images/user-login.png'),
				'image_name' => !empty(auth()->user()->profile->avatar) ? auth()->user()->profile->avatar : '',
				'role' => !empty(auth()->user()->roles()->first()->role_type) ? auth()->user()->roles()->first()->role_type: '',
				]
				])
			!!};
		</script>
	@endif
    <script>
		window.trans = <?php
		$lang_files = File::files(resource_path() . '/lang/' . App::getLocale());
		$trans = [];
		foreach ($lang_files as $f) {
			$filename = pathinfo($f)['filename'];
			$trans[$filename] = trans($filename);
		}
		echo json_encode($trans);
		?>;
    </script>
</head>
<body class="{{'lang-'.str_replace('_', '-', app()->getLocale())}} {{Helper::getTextDirection()}}">
    {{ \App::setLocale(env('APP_LANG')) }}
	<!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<!-- Wrapper Start -->
	{{ Helper::migrateNewTables() }}
	<div id="dc-wrapper" class="dc-wrapper dc-haslayout">
	@auth
        {{ Helper::displayEmailWarning() }}
    @endauth
		@yield('header')

		@yield('banner')

		@yield('main')
		
		@yield('footer')
	</div>
	<script src="{{ asset('js/vendor/jquery-3.3.1.js') }}"></script>
	@yield('bootstrap_script')
    <script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/vendor/jquery-library.js') }}"></script>
	
	<script src="{{ asset('js/vendor/typeahead/bloodhound.js') }}"></script>
	<script src="{{ asset('js/vendor/typeahead/typeahead.bundle.js') }}"></script>
	<script src="{{ asset('js/vendor/typeahead/typeahead.jquery.js') }}"></script>
	@stack('scripts')
</body>
</html>
