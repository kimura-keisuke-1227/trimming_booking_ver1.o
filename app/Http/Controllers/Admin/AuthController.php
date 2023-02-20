<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\classes\Util;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        $salon_name = Util::getSetting('サロン','salon_name_login',false);
        Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') salon_name:'.$salon_name);
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return view('admin.login',[
            'salon_name' => $salon_name,
        ]);
    }

    public function login(Request $request)
    {
        // バリデーション(フォームリクエストに書き換え可)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // ログイン情報が正しいか
        // Auth::attemptメソッドでログイン情報が正しいか検証
        if (Auth::attempt($credentials)) {
            // セッションを再生成する処理(セキュリティ対策)
            $request->session()->regenerate();

            // ミドルウェアに対応したリダイレクト(後述)
            // 下記はredirect('/admin/blogs')に類似
            return redirect()->intended('/');
        }

        // ログイン情報が正しくない場合のみ実行される処理(return すると以降の処理は実行されないため)
        // 一つ前のページ(ログイン画面)にリダイレクト
        // その際にwithErrorsを使ってエラーメッセージで手動で指定する
        // リダイレクト後のビュー内でold関数によって直前の入力内容を取得出来る項目をonlyInputで指定する
        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
{
    // ログアウト処理
    Auth::logout();
    // 現在使っているセッションを無効化(セキュリティ対策のため)
    $request->session()->invalidate();
    // セッションを無効化を再生成(セキュリティ対策のため)
    $request->session()->regenerateToken();

    return redirect() -> route('login');
}
}
 