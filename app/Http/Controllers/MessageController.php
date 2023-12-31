<?php

/**
 * Class MessageController.
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @version <PHP: 1.0.0>
 * @link    http://www.amentotech.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use DB;
use App\User;
use App\Helper;
use Auth;
use App\SiteManagement;
use Illuminate\Support\Facades\Redirect;

/**
 * Class MessageController
 *
 */
class MessageController extends Controller
{
    protected $message;

    /**
     * Create a new controller instance.
     *
     * @param mixed $message make instance
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()) {
            $senders = '';
            $senders =  $this->message::select('user_id')->where('receiver_id', auth()->user()->id)->groupBy('user_id')->get();
            $chat_settings = SiteManagement::getMetaValue('chat_settings');
            $host = !empty($chat_settings['host']) ? $chat_settings['host'] : 'http://localhost';
            $port = !empty($chat_settings['port']) ? $chat_settings['port'] : 3001;
            if (file_exists(resource_path('views/extend/back-end/chat-room/index.blade.php'))) {
                return View(
                    'extend.back-end.chat-room.index',
                    compact('senders', 'chat_settings', 'host', 'port')
                );
            } else {
                return View(
                    'back-end.chat-room.index',
                    compact('senders', 'chat_settings', 'host', 'port')
                );
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Get Users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsers()
    {
        if (Auth::user()) {
            $unreadNotifyClass  = '';
            $user_id = auth()->user()->id;
            $users = DB::select(
                DB::raw(
                    "SELECT * FROM messages
                    WHERE id IN (
                        SELECT MAX(id) AS id
                FROM (
                    SELECT id, user_id AS chat_sender
                    FROM messages
                    WHERE receiver_id = $user_id OR user_id = $user_id
                UNION ALL
                    SELECT id, receiver_id AS chat_sender
                    FROM messages
                    WHERE user_id = $user_id OR receiver_id = $user_id )
                    t GROUP BY chat_sender ) ORDER BY id DESC"
                )
            );
            $json = array();
            if (!empty($users)) {
                foreach ($users as $key => $userVal) {
                    $chat_user_id   = '';
                    if ($user_id === intval($userVal->user_id)) {
                        $chat_user_id = intval($userVal->receiver_id);
                    } else {
                        $chat_user_id = intval($userVal->user_id);
                    }
                    $json[$key]['id'] = $chat_user_id;
                    $json[$key]['image'] = asset(
                        Helper::getImage(
                            'uploads/users/' . $chat_user_id . '/',
                            User::find($chat_user_id)->profile->avatar,
                            'medium-',
                            'user-login.png'
                        )
                    );
                    $json[$key]['name'] = Helper::getUserName($chat_user_id);
                    $json[$key]['tagline'] = User::find($chat_user_id)->profile->tagline;
                    $json[$key]['image_name'] = User::find($chat_user_id)->profile->avatar;
                }
                $message_status = $this->message::where('receiver_id', $user_id)->where('status', 0)->count();
                if ($message_status > 0) {
                    $unreadNotifyClass = 'wt-dotnotification';
                }
                $json[$key]['status_class'] = $unreadNotifyClass;
                return response()->json($json);
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.user_not_found');
                return $json;
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Get user messages.
     *
     * @param mixed $request $req->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserMessages(Request $request)
    {
        $json = array();
        if (!empty($request['sender_id'])) {
            if (!empty($request['type']) && $request['type'] == 'admin') {
                $user_id = $request['sender_id'];
                $receiver_id = $request['receiver_id'];
            } else {
                $user_id = auth()->user()->id;
                $receiver_id = $request['sender_id'];
            }
            $selected_user = User::find($receiver_id);
            DB::table('messages')
                ->where('user_id', $receiver_id)
                ->where('receiver_id', $user_id)
                ->update(['status' => 1]);
            $messages = DB::table('messages')->select('*')
                ->where(
                    function ($query) use ($user_id, $receiver_id) {
                        $query->where('user_id', '=', $user_id)
                            ->Where('receiver_id', '=', $receiver_id);
                    }
                )
                ->orWhere(
                    function ($query) use ($user_id, $receiver_id) {
                        $query->where('receiver_id', '=', $user_id)
                            ->Where('user_id', '=', $receiver_id);
                    }
                )
                ->get()->toArray();
            foreach ($messages as $key => $message) {
                $message_read = '';
                if ($message->status == 1 && $message->user_id == $user_id) {
                    $message_read = 'dc-readmessage';
                }
                $json['messages'][$key]['is_sender'] = 'no';
                if ($message->user_id == $user_id) {
                    $json['messages'][$key]['is_sender'] = 'yes';
                }
                $json['messages'][$key]['id'] = $message->id;
                $json['messages'][$key]['user_id'] = $message->user_id;
                $json['messages'][$key]['image'] = asset(
                    Helper::getImage('uploads/users/' . $message->user_id.'/', User::find($message->user_id)->profile->avatar, 'medium-', 'user-login.png')
                );
                $json['messages'][$key]['message'] = $message->body;
                $json['messages'][$key]['date'] =  $message->created_at;
                $json['messages'][$key]['read_status'] = $message_read;
            }
            if ($request['type'] == 'admin') {
                $json['conversation_users']['receiver_name'] = Helper::getUserName($receiver_id);
                $json['conversation_users']['receiver_img'] = url(Helper::getProfileImage($receiver_id));
                $json['conversation_users']['receiver_role'] = Helper::getRoleNameByUserID($receiver_id);
                $json['conversation_users']['sender_name'] = Helper::getUserName($user_id);
                $json['conversation_users']['sender_img'] = url(Helper::getProfileImage($user_id));
                $json['conversation_users']['sender_role'] = Helper::getRoleNameByUserID($user_id);
            }
            $json['selected']['selected_user_name'] = Helper::getUserName($receiver_id);
            $json['selected']['selected_user_slug'] = $selected_user->slug;
            $json['selected']['selected_user_tagline'] = $selected_user->profile->tagline;
            $json['selected']['selected_user_image'] = asset(
                Helper::getImage(
                    'uploads/users/' . $receiver_id . '/',
                    User::find($receiver_id)->profile->avatar,
                    'medium-',
                    'user-login.png'
                )
            );
            $json['selected']['selected_user_verified'] = $selected_user->user_verified;
            return response()->json($json);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messsage = $this->message->sendMessage($request);
        return response()->json($messsage);
    }

     /**
     * Get conversations
     * 
     * @return \Illuminate\Http\Response
     */
    public function getConversations (Request $request) 
    {
        if (!empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $searched_users_ids = User::where('first_name', 'like', '%' . $keyword . '%')->orWhere('last_name', 'like', '%' . $keyword . '%')->pluck('id')->toarray();
            $re_create_searched_users_ids = array_values($searched_users_ids);
            $conversations = DB::table('messages')
                            ->whereIn('user_id',$re_create_searched_users_ids)
                            ->OrWhereIn('receiver_id',$re_create_searched_users_ids)
                            ->select('user_id','receiver_id')
                            ->groupBy(DB::raw('LEAST(receiver_id, user_id), GREATEST(receiver_id, user_id)'))
                            ->paginate(7);
            $pagination = $conversations->appends(
                array(
                    'keyword' => $request->get('keyword')
                )
            );
        } else {
            $conversations = DB::table('messages')
                            ->select('user_id','receiver_id')
                            ->groupBy(DB::raw('LEAST(receiver_id, user_id), GREATEST(receiver_id, user_id)'))
                            ->paginate(7);
        }
        if (file_exists(resource_path('views/extend/back-end/admin/chat/index.blade.php'))) {
            return View(
                'extend.back-end.admin.chat.index',
                compact('conversations')
            );
        } else {
            return View(
                'back-end.admin.chat.index',
                compact('conversations')
            );
        }
    }

    /**
     * Delete message
     * 
     * @return \Illuminate\Http\Response
     */
    public function deleteMessage (Request $request) 
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $json['type'] = 'error';
            $json['message'] = $server->getData()->message;
            return $json;
        }
        $json = array();
        $id = $request['id'];
        if (!empty($id)) {
            $this->message::where('id', $id)->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.message_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Delete message
     * 
     * @return \Illuminate\Http\Response
     */
    public function deleteConversation (Request $request) 
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $json['type'] = 'error';
            $json['message'] = $server->getData()->message;
            return $json;
        }
        $ids = explode('-', $request['id']);
        $user_id = $ids[0];
        $receiver_id = $ids[1];
        // dd($user_id);
        $json = array();
        if (!empty($user_id) && !empty($receiver_id)) {
            $this->message::where(
                function ($query) use ($user_id, $receiver_id) {
                    $query->where('user_id', '=', $user_id)
                        ->Where('receiver_id', '=', $receiver_id);
                })
                ->orWhere(
                    function ($query) use ($user_id, $receiver_id) {
                        $query->where('receiver_id', '=', $user_id)
                            ->Where('user_id', '=', $receiver_id);
                    }
                )->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.message_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
