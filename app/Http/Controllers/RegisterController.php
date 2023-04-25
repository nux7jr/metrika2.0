<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Telegram\TwoFactorCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\returnSelf;

class RegisterController extends ModelController
{
    /**
     * @var array 'method' => 'policy'
     */
    protected $guardedMethods = [
        'export' => 'export',
        'import' => 'import',
    ];
    protected $methodsWithoutModels = ['import'];
    protected function getModelClass(): string
    {
        return User::class;
    }
    public function index()
    {
        // return Auth::user()->hasRole("super-admin");
        if (!Auth::user()->hasRole("super-admin")) return Auth::user()->hasRole("super-admin");
        return view('create.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:50'],
            'login' => ['required', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:7', 'max:50', 'confirmed'],
            'telegram_chat_id' => ['required', 'digits_between:5,15', 'unique:users,telegram_chat_id'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->get('*') as $key => $error) {
                $message['errors'][$key] = implode($error);
            }
            return $message;
        }
        try {
            $user = User::query()->create([
                'name' => $request->input('name'),
                'login' => $request->input('login'),
                'password' => $request->input('password'),
                'telegram_chat_id' => $request->input('telegram_chat_id')
            ]);

            if (Auth::attempt(['login' => $validator->getData()['login'], 'password' => $validator->getData()['password']])) {
                Auth::user();
                $this->authenticated($request, $user);
                $message['url'] = route('verify.index');
                return $message;
            }
        } catch (Exeption $error) {
        }
        return ['error' => 'Что-то пошло не так!'];
    }

    protected function authenticated(Request $request, $user)
    {
        $user->generateTwoFactorCode();
        TwoFactorCode::sendTelegramCode($user);
    }
}
