@extends('back-end.master')
@push('backend_stylesheets')
<link href="{{ asset('css/basictable.css') }}" rel="stylesheet">
@endpush
@section('content')
@include('includes.pre-loader')
<div id="message_center">
    <section class="dc-haslayout">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7 col-xl-6">
                <div class="dc-dashboardbox">
                    <div class="dc-dashboardboxtitle dc-titlewithsearch dc-titlewithdel">
                        <h2>{{{ trans('lang.conversations') }}}</h2>
                        <div class="dc-rightarea">
                            {!! Form::open(['url' => url('admin/conversations/search'),
                            'method' => 'get', 'class' => 'dc-formtheme dc-formsearch'])
                            !!}
                            <fieldset>
                                <div class="form-group">
                                    <input type="text" name="keyword"
                                        value="{{{ !empty($_GET['keyword']) ? $_GET['keyword'] : '' }}}"
                                        class="form-control" placeholder="{{{ trans('lang.ph_search_participants') }}}">
                                    <button type="submit" class="dc-searchgbtn"><i
                                            class="lnr lnr-magnifier"></i></button>
                                </div>
                            </fieldset>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    @if ($conversations->count() > 0)
                    <div class="dc-dashboardboxcontent dc-categoriescontentholder">
                        <table class="dc-tablecategories dc-table-responsive" id="checked-val">
                            <thead>
                                <tr>
                                    <th>{{{ trans('lang.participants') }}}</th>
                                    <th>{{{ trans('lang.action') }}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 0; @endphp
                                @foreach ($conversations as $conv)
                                    @php
                                        $user = \App\User::find($conv->user_id);
                                        $user_role = !empty($user->id) ? Helper::getRoleTypeByUserID($user->id) : '';
                                        $user_route = !empty($user_role) && $user_role !== 'regular' ? url(route('userProfile', ['slug' => $user->slug])) : 'javascript:void(0);';
                                        $user_name = \App\Helper::getUserName($conv->user_id);
                                        $receiver =  \App\User::find($conv->receiver_id);
                                        $reciever_role = !empty($receiver->id) ? Helper::getRoleTypeByUserID($receiver->id) : '';
                                        $receiver_route = !empty($reciever_role) && $reciever_role !== 'regular' ? url(route('userProfile', ['slug' => $receiver->slug])) : 'javascript:void(0);';
                                        $receiver_name = \App\Helper::getUserName($conv->receiver_id);
                                    @endphp
                                    <tr class="del-{{$conv->user_id}}-{{$conv->receiver_id}}">
                                        <td>
                                            <a href="{{{ $user_route }}}">{{{ $user_name }}}</a> , <a href="{{{ $receiver_route }}}">{{ $receiver_name }}</a>
                                        </td>
                                        <td>
                                            <div class="dc-actionbtn">
                                                <delete :title="'{{trans("lang.ph_delete_confirm_title")}}'" :id="'{{$conv->user_id}}-{{$conv->receiver_id}}'" :message="'{{trans("lang.ph_conversation_delete_message")}}'" :url="'{{url('admin/conversation/delete')}}'"></delete>
                                                <a v-on:click="viewConversation({{$conv->user_id}}, {{$conv->receiver_id}})" class="dc-addinfo dc-skillsaddinfo">
                                                    <i class="lnr lnr-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $counter++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                        @if ( method_exists($conversations,'links') )
                            {{ $conversations->links('pagination.custom') }}
                        @endif
                    </div>
                    @else
                    @include('errors.no-record')
                    @endif
                </div>
            </div>
            <transition name="fade">
                <conversation v-if="showConversation" :messages='messages' :conversation_users='users'></conversation>
            </transition>
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