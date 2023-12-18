@extends('back-end.master')
@push('backend_stylesheets')
    <link rel="stylesheet" href="{{ asset('css/chosen.css') }}">
@endpush
@section('content')
@include('includes.pre-loader')
    <section class="dc-haslayout" id="doctor_appointments">
        @if (Session::has('message'))
            <div class="flash_msg">
                <flash_messages :message_class="'success'" :time ='5' :message="'{{{ Session::get('message') }}}'" v-cloak></flash_messages>
            </div>
        @elseif (Session::has('error'))
            <div class="flash_msg">
                <flash_messages :message_class="'danger'" :time ='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
            </div>
        @endif
        <appointments :type="'{{$user_type}}'" prescription_route="{{ route('add-prescription') }}"></appointments>
    </section>
@endsection
@push('backend_scripts')
    <script src="{{ asset('js/chosen.jquery.js') }}"></script>
    <script>
        jQuery(".dc-chosen-select").chosen();
    </script>
@endpush
