@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')
@include('includes.pre-loader')
    @php 
        $general_settings  = App\SiteManagement::getMetaValue('general_settings');
        $selected_search_form_type = !empty($general_settings) && !empty($general_settings['search_form_type']) ? $general_settings['search_form_type'] : 'global_searching'; 
    @endphp
    <div class="pages-listing" id="pages">
        <section class="dc-haslayout">
            <div class="row">
                <page-create 
                    section_list="{{json_encode($sections)}}" 
                    :layout_list="'{{json_encode($layouts)}}'"
                    :selected_search_form_type ="'{{$selected_search_form_type}}'"
                />
            </div>
        </section>
    </div>
@endsection
@push('backend_scripts')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea.dc-tinymceeditor',
            height: 300,
            theme: 'modern',
            plugins: ['code advlist autolink lists link image charmap print preview hr anchor pagebreak'],
            menubar: false,
            statusbar: false,
            toolbar1: 'undo redo | insert | image | styleselect | bold italic | alignleft aligncenter alignright alignjustify code',
            image_advtab: true,
            inline_styles : true,
            remove_script_host: false,
            extended_valid_elements  : "span[style],i[class]",
            relative_urls: false
        })
    </script>
@endpush
