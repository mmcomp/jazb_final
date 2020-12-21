<?php

namespace App\Http\Middleware;

use Closure;
use App\Circular;
use App\CircularUsers;
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
        $circularUsers = CircularUsers::where('users_id', auth()->user()->id)->get();
        $circulars = Circular::whereNotIn('id', $circularUsers->pluck('circulars_id'))->get();
        MessageFlow::where('users_id', auth()->user()->id)->update([
            'status'=>'read'
        ]);

        \View::share('usermessages', $messages);
        \View::share('usercircular', $circulars);
        return $next($request);
    }
}
