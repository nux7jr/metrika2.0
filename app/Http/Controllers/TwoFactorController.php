<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Telegram\TwoFactorCode;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('login.telegramAuth');
    }
    public function store(Request $request)
    {

        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);
        $user = auth()->user();
        if ($request->input('two_factor_code') == $user->two_factor_code) {
            $user->resetTwoFactorCode();
            $message['url'] = route('home');
            return $message;
        }
        if (
            $request->input('two_factor_code') != $user->two_factor_code &&
            $user->two_factor_expires_at < now()
        ) {
            $this->resend();
        }
        $message['error'] = "Введенный вами двухфакторный код не совпадает";
        return $message;
    }
    public function resend()
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        TwoFactorCode::sendTelegramCode($user);
        $message['error'] = "Двухфакторный код отправлен снова";
        return $message;
    }
}
