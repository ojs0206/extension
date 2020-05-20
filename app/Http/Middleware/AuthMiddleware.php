<?php


namespace App\Http\Middleware;

use Closure;
use Closure as ClosureAlias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class AuthMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param ClosureAlias $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user_id = session()->get(SESS_UID);
        Log::info($user_id);

        if ($user_id == '' || $user_id == null || $user_id == 0)
            return redirect('/');

        return $next($request);
    }


}