@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')
@include('includes.pre-loader')
    <div class="pages-listing" id="pages">
        @if (Session::has('message'))
            <div class="flash_msg">
                <flash_messages :message_class="'success'" :time='5' :message="'{{{ Session::get('message') }}}'" v-cloak></flash_messages>
            </div>
        @elseif (Session::has('error'))
            <div class="flash_msg">
                <flash_messages :message_class="'danger'" :time ='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
            </div>
        @endif
        @if ($errors->any())
            <ul class="dc-jobalerts">
                @foreach ($errors->all() as $error)
                    <div class="flash_msg">
                        <flash_messages :message_class="'danger'" :time='10' :message="'{{{ $error }}}'" v-cloak></flash_messages>
                    </div>
                @endforeach
            </ul>
        @endif
        @php 
            $general_settings  = App\SiteManagement::getMetaValue('general_settings');
            $selected_search_form_type = !empty($general_settings) && !empty($general_settings['search_form_type']) ? $general_settings['search_form_type'] : 'global_searching'; 
        @endphp
        <section class="dc-haslayout">
            <page-edit 
                :page_id="'{{$id}}'" 
                :selected_parent="'{{$parent_selected_id}}'" 
                section_list="{{json_encode($sections)}}" 
                :layout_list="'{{json_encode($layouts)}}'"
                :selected_search_form_type ="'{{$selected_search_form_type}}'"
            />
        </section>
    </div>
@endsection
@section('bootstrap_script')
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
            relative_urls: false,
            extended_valid_elements  : "span[style],i[class]"
        })
    </script>
@stop
@push('backend_scripts')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
@endpush
