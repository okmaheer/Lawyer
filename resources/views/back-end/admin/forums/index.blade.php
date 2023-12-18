@extends('back-end.master')
@push('backend_stylesheets')
<link href="{{ asset('css/basictable.css') }}" rel="stylesheet">
@endpush
@section('content')
@include('includes.pre-loader')
<div class="dc-forums" id="health_forum">
    @if (Session::has('message'))
        <div class="flash_msg">
            <flash_messages :message_class="'success'" :time='5' :message="'{{{ Session::get('message') }}}'" v-cloak>
            </flash_messages>
        </div>
    @elseif (Session::has('error'))
        <div class="flash_msg">
            <flash_messages :message_class="'danger'" :time='5' :message="'{{{ Session::get('error') }}}'" v-cloak>
            </flash_messages>
        </div>
    @endif
    <section class="dc-haslayout">
        <div class="row">
            <div class="col-12 float-right">
                <div class="dc-dashboardbox">
                    <div class="dc-dashboardboxtitle dc-titlewithsearch dc-titlewithdel">
                        <h2>{{{ trans('lang.manage_forums') }}}</h2>
                        <div class="dc-rightarea">
                            {!! Form::open(['url' => url('admin/forums/search'),
                            'method' => 'get', 'class' => 'dc-formtheme dc-formsearch'])
                            !!}
                            <fieldset>
                                <div class="form-group">
                                    <input type="text" name="keyword"
                                        value="{{{ !empty($_GET['keyword']) ? $_GET['keyword'] : '' }}}"
                                        class="form-control" placeholder="{{{ trans('lang.search_forums') }}}">
                                    <button type="submit" class="dc-searchgbtn"><i
                                            class="lnr lnr-magnifier"></i></button>
                                </div>
                            </fieldset>
                            {!! Form::close() !!}
                            <multi-delete v-cloak v-if="this.is_show"
                                :title="'{{trans("lang.ph.delete_confirm_title")}}'"
                                :message="'{{trans("lang.ph.forums_del_delete_message")}}'"
                                :url="'{{url('admin/forums/delete-checked-forums')}}'"
                                :redirect_url="'{{url('admin/forums')}}'">
                            </multi-delete>
                        </div>
                    </div>
                    @if ($forums->count() > 0)
                    <div class="dc-dashboardboxcontent dc-categoriescontentholder">
                        <table class="dc-tablecategories dc-table-responsive" id="checked-val">
                            <thead>
                                <tr>
                                    <th>
                                        <span class="dc-checkbox">
                                            <input name="forums[]" id="dc-forums" @click="selectAll" type="checkbox">
                                            <label for="dc-forums"></label>
                                        </span>
                                    </th>
                                    <th>{{{ trans('lang.name') }}}</th>
                                    <th>{{{ trans('lang.slug') }}}</th>
                                    <th>{{{ trans('lang.action') }}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 0; @endphp
                                @foreach ($forums as $key => $forum)
                                    <tr class="del-{{{ $forum->id }}}">
                                        <td>
                                            <span class="dc-checkbox">
                                                <input name="locs[]" @click="selectRecord" value="{{{ intVal(clean($forum->id)) }}}"
                                                    id="wt-check-{{{ $counter }}}" type="checkbox">
                                                <label for="wt-check-{{{ $counter }}}"></label>
                                            </span>
                                        </td>
                                        <td>{{{ html_entity_decode(clean($forum->question_title)) }}}</td>
                                        <td>{{{ html_entity_decode(clean($forum->slug)) }}}</td>
                                        <td>
                                            <div class="dc-actionbtn">
                                                @if ($forum->answers->count() > 0 && !empty($forum->answers))
                                                    <a href="{{{ url('admin/question/answers') }}}/{{{ html_entity_decode(clean($forum->id)) }}}"
                                                        class="dc-view-answers dc-skillsaddinfo">
                                                        <i class="ti-comments"></i>
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0);"
                                                        class="dc-view-answers dc-skillsaddinfo disable-answer">
                                                        <i class="ti-comments"></i>
                                                    </a>
                                                @endif
                                                <a href="{{{ url('health-forum') }}}/{{{ html_entity_decode(clean($forum->slug)) }}}"
                                                    class="dc-view-info dc-skillsaddinfo">
                                                    <i class="lnr lnr-eye"></i>
                                                </a>
                                                <a href="{{{ url('admin/edit-forums') }}}/{{{ html_entity_decode(clean($forum->id)) }}}"
                                                    class="dc-addinfo dc-skillsaddinfo">
                                                    <i class="lnr lnr-pencil"></i>
                                                </a>
                                                <delete :title="'{{trans("lang.ph_delete_confirm_title")}}'"
                                                    :id="'{{ intVal(clean($forum->id)) }}'"
                                                    :message="'{{trans("lang.ph_forum_delete_message")}}'"
                                                    :url="'{{url('admin/forums/delete')}}'"
                                                    :redirect_url="'{{url('admin/forums')}}'">
                                                </delete>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $counter++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                        @if ( method_exists($forums,'links') )
                            {{ $forums->links('pagination.custom') }}
                        @endif
                    </div>
                    @else
                        @include('errors.no-record')
                    @endif
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