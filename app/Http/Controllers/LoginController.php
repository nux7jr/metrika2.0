<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Telegram\TwoFactorCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{

    public function index()
    {
        return view('login.index');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:7', 'max:50'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->get('*') as $key => $error) {
                $message['errors'][$key] = implode($error);
            }
            return $message;
        }
        $credentials = ['login' => $request->input('login'), 'password' => $request->input('password')];
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $this->authenticated($request, Auth::user());
            $message['url'] = route('verify.index');
            return $message;
        } else {
            $message['errors']["login"] = "Ошибка авторизации";
            return $message;
        }
    }
    protected function authenticated(Request $request, $user)
    {
        $user->generateTwoFactorCode();
        TwoFactorCode::sendTelegramCode($user);
    }
}
