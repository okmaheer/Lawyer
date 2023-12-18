@extends('master')

@push('stylesheets')
	<link href="{{ asset('css/tipso.css') }}" rel="stylesheet">
	<link href="{{ asset('css/scrollbar.css') }}" rel="stylesheet">
	@stack('front_end_stylesheets')
@endpush

@push('PackageStyle')
    <link href="{{ asset('css/dd.css') }}" rel="stylesheet">
@endpush

@section('header')
	@include('front-end.includes.header')
@endsection
@php 
	$inner_page_settings = !empty(App\SiteManagement::getMetaValue('inner_page_data')) ? App\SiteManagement::getMetaValue('inner_page_data') : array();
@endphp

@if (!empty($inner_page_settings['show_search_form']) && $inner_page_settings['show_search_form'] == 'true')
	@section('banner')
		@include('front-end.includes.inner-banner')
	@endsection
@endif

@section('main')
<main id="dc-main" class="dc-main dc-haslayout">
	@yield('content')
</main>
@endsection

@section('footer')
	@include('front-end.includes.footer')
@endsection

@push('scripts')
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/tipso.js') }}"></script>
<script src="{{ asset('js/jquery.dd.min.js') }}"></script>	
<script src="{{ asset('js/scrollbar.min.js') }}"></script>
<script>
	jQuery('.dc-tipso').tipso({
    	tooltipHover: true
	});
	jQuery(document).ready(function(e) {
		try {
			jQuery("body select.locations").msDropDown();
			} catch(e) {
			alert(e.message);
		}
	});
</script>
@stack('front_end_scripts')
@endpush
