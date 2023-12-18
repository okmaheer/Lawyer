@extends('back-end.master')
@push('backend_stylesheets')
    <link href="{{ asset('css/basictable.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('includes.pre-loader')
    <div class="dc-appnt_intrv" id="prescription">
        @if (Session::has('message'))
            <div class="flash_msg">
                <flash_messages :message_class="'success'" :time ='5' :message="'{{{ Session::get('message') }}}'" v-cloak></flash_messages>
            </div>
        @elseif (Session::has('error'))
            <div class="flash_msg">
                <flash_messages :message_class="'danger'" :time ='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
            </div>
        @endif
        <section class="dc-haslayout">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7 col-xl-6">
                    <div class="dc-haslayout dc-dbsectionspace">
                        <div class="dc-dashboardbox">
                            <div class="dc-dashboardboxtitle dc-titlewithsearch dc-titlewithdel">
                                <h2>{{ trans('lang.manage_medicine_durations') }}</h2>
                                <div class="dc-rightarea">
                                    {!! Form::open(['url' => url('admin/medicine-duration/search'),
                                        'method' => 'get', 'class' => 'dc-formtheme dc-formsearch'])
                                    !!}
                                    <fieldset>
                                        <div class="form-group">
                                            <input type="text" name="keyword" value="{{{ !empty($_GET['keyword']) ? $_GET['keyword'] : '' }}}"
                                                class="form-control" placeholder="{{{ trans('lang.ph.search_medicine_duration') }}}">
                                            <button type="submit" class="dc-searchgbtn"><i class="lnr lnr-magnifier"></i></button>
                                        </div>
                                    </fieldset>
                                    {!! Form::close() !!}
                                    <multi-delete
                                        v-cloak
                                        v-if="this.is_show"
                                        :title="'{{trans("lang.ph.delete_confirm_title")}}'"
                                        :message="'{{trans("lang.medicine_durations_delete_message")}}'"
                                        :url="'{{url('admin/delete-checked-medicine-duration')}}'"
                                        :redirect_url="'{{url('admin/prescription/medicine-durations')}}'"
                                    >
                                    </multi-delete>
                                </div>
                            </div>
                            @if ($medicine_durations->count() > 0)
                                <div class="dc-dashboardboxcontent dc-categoriescontentholder">
                                    <table class="dc-tablecategories dc-table-responsive" id="checked-val">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <span class="dc-checkbox">
                                                        <input name="medicine_durations[]" id="dc-medicine-durations" @click="selectAll('dc-medicine-durations')" type="checkbox">
                                                        <label for="dc-medicine-durations"></label>
                                                    </span>
                                                </th>
                                                <th>{{{ trans('lang.name') }}}</th>
                                                <th>{{{ trans('lang.action') }}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $counter = 0; @endphp
                                            @foreach ($medicine_durations as $item)
                                                <tr class="del-{{{ $item->id }}}">
                                                    <td>
                                                        <span class="wt-checkbox">
                                                            <input name="medicine_durations[]" @click="selectRecord" value="{{{ $item->id }}}" id="wt-check-{{{ $counter }}}" type="checkbox">
                                                            <label for="wt-check-{{{ $counter }}}"></label>
                                                        </span>
                                                    </td>
                                                    <td>{{{ $item->title }}}</td>
                                                    <td>
                                                        <div class="dc-actionbtn">
                                                            <a href="{{{ url('admin/medicine-duration/edit') }}}/{{{ $item->id }}}" class="dc-addinfo dc-skillsaddinfo">
                                                                <i class="lnr lnr-pencil"></i>
                                                            </a>
                                                            <delete :title="'{{trans("lang.ph_delete_confirm_title")}}'" :id="'{{$item->id}}'" :message="'{{trans("lang.ph_medicine_duration_delete_message")}}'" :url="'{{url('admin/medicine-duration/delete')}}'"></delete>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php $counter++; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if ( method_exists($medicine_durations,'links') )
                                        {{ $medicine_durations->links('pagination.custom') }}
                                    @endif
                                </div>
                            @else
                                @include('errors.no-record')
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <div class="dc-haslayout dc-dbsectionspace">
                        <div class="dc-dashboardbox">
                            <div class="dc-dashboardboxtitle">
                                <h2>{{{ trans('lang.add_new_medicine_duration') }}}</h2>
                            </div>
                            <div class="dc-dashboardboxcontent dc-addservices">
                                {!! Form::open(['url' => url('admin/store-medicine-duration'), 'class' => 'dc-formtheme dc-formsearch'])!!}
                                    <fieldset>
                                        <div class="form-group">
                                            {!! Form::text( 'title', null, ['class' =>'form-control'.($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => trans('lang.ph.medicine_duration_title')] ) !!}
                                            @if ($errors->has('title'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('title') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group dc-btnarea">
                                            {!! Form::submit(trans('lang.add_medicine_duration'), ['class' => 'dc-btn']) !!}
                                        </div>
                                    </fieldset>
                                {!! Form::close(); !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
@stack('backend_scripts')
<script src="{{ asset('js/jquery.basictable.min.js') }}"></script>
<script type="text/javascript">
    jQuery('.dc-table-responsive').basictable({
            breakpoint: 767,
    });
</script>
@endpush