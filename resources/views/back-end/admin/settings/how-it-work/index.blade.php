@extends('back-end.master')
@section('content')
@include('includes.pre-loader')
<div class="dc-haslayout dc-manage-account la-setting-holder" id="settings">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
            <div class="dc-dashboardbox dc-dashboardtabsholder dc-accountsettingholder">
                <div class="dc-dashboardtabs">
                    <ul class="dc-tabstitle nav navbar-nav">
                        <li class="nav-item">
                            <a class="active" data-toggle="tab" href="#dc-how-works-settings">{{ trans('lang.how_works_section') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="dc-tabscontent tab-content">
                    @include('back-end.admin.settings.how-it-work.how-it-works-section')
                    @include('back-end.admin.settings.how-it-work.how-it-works-tabs')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('backend_scripts')
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea.dc-tinymceeditor',
            height: 300,
            theme: 'modern',
            plugins: ['code advlist autolink lists link image charmap print preview hr anchor pagebreak'],
            menubar: false,
            statusbar: false,
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify code',
            image_advtab: true,
            remove_script_host: false,
            })
    </script>
@endpush
