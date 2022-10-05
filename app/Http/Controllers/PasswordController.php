<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\UserTokenRepositoryInterface;
use App\Http\Requests\SendEmailRequest;
use App\Mail\UserResetPasswordMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ResetPasswordRequest;
use Exception;

class PasswordController extends Controller
{
    private $userRepository;
    private $userTokenRepository;

    private const MAIL_SENDED_SESSION_KEY = 'user_reset_password_mail_sended_action';
    private const UPDATE_PASSWORD_SESSION_KEY = 'user_update_password_action';

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserTokenRepositoryInterface $userTokenRepository,
    )
    {
        $this->userRepository = $userRepository;
        $this->userTokenRepository = $userTokenRepository;
    }

    /**
    * パスワード再設定メール送信フォームページ
    *
    * @return \Illuminate\Contracts\View\View
    */
    public function emailFormResetPassword()
    {
        return view('user.reset_password.email_form');
    }

     /**
    * ユーザーのパスワード再設定メール送信
    *
    * @param SendEmailRequest $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function sendEmailResetPassword(SendEmailRequest $request)
    {   
        try {
            $user = $this->userRepository->findFromEmail($request->email);
            $userToken = $this->userTokenRepository->updateOrCreateUserToken($user->id);
            Log::info(__METHOD__ . '...ID:' . $user->id . 'のユーザーにパスワード再設定用メールを送信します。');
            Mail::send(new UserResetPasswordMail($user, $userToken));
            Log::info(__METHOD__ . '...ID:' . $user->id . 'のユーザーにパスワード再設定用メールを送信しました。');
        } catch(Exception $e) {
            Log::error(__METHOD__ . '...ユーザーへのパスワード再設定用メール送信に失敗しました。 request_email = ' . $request->email . ' error_message = ' . $e);
            return redirect()->route('password_reset.email.form')
                ->with('error', '処理に失敗しました。時間をおいて再度お試しください。');
        }
        // メール送信完了画面への不正アクセスを防ぐためのセッションキー
        session()->put(self::MAIL_SENDED_SESSION_KEY, 'user_reset_password_send_email');

        return redirect()->route('password_reset.email.send_complete');
    }

       /**
    * ユーザーのパスワードリセットメール送信完了ページ
    *
    * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    */
    public function sendComplete()
    {
       // メール送信処理で保存したセッションキーに値がなければアクセスできないようにすることで不正アクセスを防ぐ
        if (session()->pull(self::MAIL_SENDED_SESSION_KEY) !== 'user_reset_password_send_email') {
            return redirect()->route('password_reset.email.form')
                ->with('flash_message', '不正なリクエストです。');
        }

        return view('user.reset_password.send_complete');
    }

        /**
    * ユーザーのパスワード再設定フォーム画面
    *
    * @param Request $request
    * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    */
    public function edit(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'URLの有効期限が過ぎたためエラーが発生しました。パスワードリセットメールを再発行してください。');
        }
        $resetToken = $request->reset_token;
        try {
            $userToken = $this->userTokenRepository->getUserTokenfromToken($resetToken);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' UserTokenの取得に失敗しました。 error_message = ' . $e);
            return redirect()->route('password_reset.email.form')
                ->with('flash_message', __('パスワードリセットメールに添付されたURLから遷移してください。'));
        }

        return view('user.reset_password.edit')
            ->with('userToken', $userToken);
    }

        ##ここから追加##
    /**
    * パスワード更新処理
    *
    * @param ResetPasswordRequest $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function update(ResetPasswordRequest $request)
    {
        try {
            $userToken = $this->userTokenRepository->getUserTokenfromToken($request->reset_token);
            $this->userRepository->updateUserPassword($request->password, $userToken->user_id);
            Log::info(__METHOD__ . '...ID:' . $userToken->user_id . 'のユーザーのパスワードを更新しました。');
        } catch (Exception $e) {
            Log::error(__METHOD__ . '...ユーザーのパスワードの更新に失敗しました。...error_message = ' . $e);
            return redirect()->route('password_reset.email_form')
                ->with('flash_message', __('処理に失敗しました。時間をおいて再度お試しください。'));
        }
        // パスワードリセット完了画面への不正アクセスを防ぐためのセッションキー
        $request->session()->put(self::UPDATE_PASSWORD_SESSION_KEY, 'user_update_password');

        return redirect()->route('password_reset.edited');
    }

        /**
    * パスワードリセット完了ページ
    *
    * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    */
    public function edited()
    {
        // パスワード更新処理で保存したセッションキーに値がなければアクセスできないようにすることで不正アクセスを防ぐ
        if (session()->pull(self::UPDATE_PASSWORD_SESSION_KEY) !== 'user_update_password') {
            return redirect()->route('password_reset.email.form')
                ->with('flash_message', '不正なリクエストです。');
        }

        return view('user.reset_password.edited');
    }
}
