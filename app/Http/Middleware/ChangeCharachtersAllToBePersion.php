<?php

namespace App\Http\Middleware;

use Closure;

class ChangeCharachtersAllToBePersion
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
        $input = $request->all();
        foreach ($input as $key => $value) {
            if (strpos($key, "name") !== false) {
                $input[$key] = str_replace(array('ي', 'ك'), array('ی', 'ک'), $value);
                $request->replace($input);
            }
            if(strpos($key,"phone") !== false){
                $input[$key] = str_replace(["۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "۰"],["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"],$value);
                $request->replace($input);
            }
        }
        return $next($request);
    }
}
