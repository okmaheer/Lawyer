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
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7 col-xl-6">
                <div class="dc-dashboardbox">
                    <div class="dc-dashboardboxtitle dc-titlewithsearch dc-titlewithdel">
                        <h2>{{{ trans('lang.manage_forum_answers') }}}</h2>
                        <div class="dc-rightarea">
                            <multi-delete v-cloak v-if="this.is_show"
                                :title="'{{trans("lang.ph.delete_confirm_title")}}'"
                                :message="'{{trans("lang.ph.forums_del_delete_message")}}'"
                                :url="'{{url('admin/answers/delete-checked-answers')}}'"
                                :redirect_url="'{{url('admin/question/answers/'.$forum->id)}}'">
                            </multi-delete>
                        </div>
                    </div>
                    @if (!empty($answers))
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
                                        <th>{{{ trans('lang.author_name') }}}</th>
                                        <th>{{{ trans('lang.answer') }}}</th>
                                        <th>{{{ trans('lang.action') }}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 0; @endphp
                                    @foreach ($answers as $answer)
                                        @php $user = App\User::find($answer->pivot->user_id); @endphp
                                        <tr class="del-{{{ $answer->pivot->id }}}">
                                            <td>
                                                <span class="dc-checkbox">
                                                    <input name="asnwers[]" @click="selectRecord" value="{{{ intVal(clean($answer->pivot->id)) }}}"
                                                        id="wt-check-{{{ $counter }}}" type="checkbox">
                                                    <label for="wt-check-{{{ $counter }}}"></label>
                                                </span>
                                            </td>
                                            <td>{{{ Helper::getUserName($user->id) }}}</td>
                                            <td><a href="javascript:void(0);" v-on:click.prevent="displayAnswer('answerModel-{{$answer->pivot->id}}')">{{{ trans('lang.view_update_answer') }}}</a></td>
                                            <td>
                                                <div class="dc-actionbtn">
                                                    <delete :title="'{{trans("lang.ph_delete_confirm_title")}}'"
                                                        :id="'{{ intVal(clean($answer->pivot->id)) }}'"
                                                        :message="'{{trans("lang.ph_ans_delete_message")}}'"
                                                        :url="'{{url('admin/answer/delete')}}'"
                                                        :redirect_url="'{{url('admin/question/answers/'.$forum->id)}}'">
                                                    </delete>
                                                </div>
                                            </td>
                                        </tr>
                                        <b-modal size="lg" ref="answerModel-{{$answer->pivot->id}}" hide-footer title="{{trans('lang.answer')}}" v-cloak>
                                            <div class="dc-formtheme dc-answerform ">
                                                <fieldset>
                                                    <div class="form-group">
                                                        {!! Form::textarea('forum_answer', $answer->pivot->answer, ['class' => 'form-control', 'id' => 'answer-'.$answer->pivot->id, 'placeholder' => trans('lang.answer')]) !!}
                                                    </div>
                                                    <div class="form-group dc-btnarea">
                                                        <a href="javascript:void(0);" class="dc-btn"  v-on:click.prevent="updateAnswer('{{$answer->pivot->id}}', 'answer-{{$answer->pivot->id}}')"> {{trans('lang.update')}} </a>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </b-modal>
                                        @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            @if ( method_exists($answers,'links') )
                                {{ $answers->links('pagination.custom') }}
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