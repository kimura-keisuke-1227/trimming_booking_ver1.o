<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

class CheckAdminAuth
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
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        $user = Auth::user();
        
        // dd($user->auth);

        Log::debug(__METHOD__ . '(' . __LINE__ . ')$request->path():' . $request->path());

        $if_contain_admin = str_contains($request->path(), 'admin');
        Log::debug(__METHOD__ . '(' . __LINE__ . ')if_contain_admin:' . $if_contain_admin );
        
        // authが1以外のユーザーはアクセスを禁止
        if (!$user || $user->auth !== 1) {
            Log::warning(__METHOD__ . '(' . __LINE__ . ')' . ' this_user_is_not_admin_user_so_redirect_to_top.');
            return redirect('/')->with('error', '管理画面にはアクセスできません');
        }
        
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return $next($request);
    }
}