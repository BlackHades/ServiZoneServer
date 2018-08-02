<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use App\Tokens;
use App\User;

class FincoAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $token = Tokens::where("token", $request->token)
                ->where("user_id", $request->user_id)
                ->get();

        if (count($token) > 0) {
            $user = User::where('id', $request->user_id)->first();
            $request->route()->setParameter('user', $user);
            return $next($request);
        } else {
            $status = "error";
            $message = "You are not authorized";
            return response()->json(compact('status', 'message'));
        }
    }
}