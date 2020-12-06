<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Message;
use App\User;
use App\MessageFlow;

class MessageController extends Controller
{
    public function index($id = null){
        $user = null;
        if($id==null){
            $id = Auth::user()->id;
        }else{
            $user = User::find($id);
        }
        $messageIds = MessageFlow::where('users_id', $id)->pluck('messages_id');
        $messages = Message::where('is_deleted', false)->whereIn('id', $messageIds)->with('flows.user')->with('user')->get();
        // dd(Str::limit($messages[0]->message, 10));
        return view('messages.index',[
            'user'=>$user,
            'messages' => $messages,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function indexOutbox($id = null){
        $user = null;
        if($id==null){
            $id = Auth::user()->id;
        }else{
            $user = User::find($id);
        }
        $messageIds = MessageFlow::where('sender_id', $id)->pluck('messages_id');
        $messages = Message::where('is_deleted', false)->whereIn('id', $messageIds)->with('flows.user')->with('user')->get();
        // dd(Str::limit($messages[0]->message, 10));
        return view('messages.outbox',[
            'user'=>$user,
            'messages' => $messages,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request, $id = null)
    {
        $users = User::where('is_deleted', false)->orderBy('last_name')->get();
        $message = new Message;
        if($id!=null){
            $message = Message::find($id);
            if($message==null){
                $message = new Message;
            }else{
                // dd($message);
            }
        }
        if($request->getMethod()=='GET'){
            return view('messages.create', [
                "users"=>$users,
                "message"=>$message
            ]);
        }

        $attachment = null;

        if($request->file('attachment')){
            $filename = now()->timestamp . '.' . $request->file('attachment')->extension();
            $attachment = $request->file('attachment')->storeAs('messages', $filename, 'public_uploads');
        }

        $message->users_id = Auth::user()->id;
        $message->message = $request->input('message');
        $message->attachment = $attachment;
        $message->save();

        if($request->input('recievers_id')==null) {
            $request->session()->flash("msg_error", "پیام گیرنده ندارد.");
            return redirect()->route('messages');
        }

        foreach($request->input('recievers_id') as $reciever_id){
            $messageFlow = new MessageFlow;
            $messageFlow->messages_id = $message->id;
            $messageFlow->sender_id = $message->users_id;
            $messageFlow->users_id = $reciever_id;
            $messageFlow->attachment = $attachment;
            $messageFlow->save();
        }

        if($request->input('ccs_id'))
            foreach($request->input('ccs_id') as $reciever_id){
                $messageFlow = new MessageFlow;
                $messageFlow->messages_id = $message->id;
                $messageFlow->sender_id = $message->users_id;
                $messageFlow->users_id = $reciever_id;
                $messageFlow->type = 'cc';
                $messageFlow->attachment = $attachment;
                $messageFlow->save();
            }

        $request->session()->flash("msg_success", "پیام با موفقیت ارسال شد.");
        return redirect()->route('messages');
    }

    public function userIndex($id) {
        return $this->index($id);
    }

    public function userCreate($id){
        $users = User::where('is_deleted', false)->orderBy('last_name')->get();
        if(request()->getMethod()=='GET'){
            return view('messages.create', [
                "id"=>$id,
                "users"=>$users
            ]);
        }
    }

    public function messageCreate($id){
        return $this->create(request(), $id);
    }
}
