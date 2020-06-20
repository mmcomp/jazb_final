<?php

namespace App\Http\Middleware;

use Closure;
use App\Message;
use App\MessageFlow;

class MessageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $messages = MessageFlow::where('users_id', auth()->user()->id)->where('status', 'unread')->with('themessage.user')->with('sender')->get();
        MessageFlow::where('users_id', auth()->user()->id)->update([
            'status'=>'read'
        ]);

        \View::share('usermessages', $messages);
        return $next($request);
    }
}
