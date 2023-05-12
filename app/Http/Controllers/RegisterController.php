<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
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
        return view('create.index')->with("title", "Создать пользователя");
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
                $assign = Auth::user();
                $assign->assignRole('user');
                $this->authenticated($request, $assign);
                $message['url'] = route('verify.index');
                return $message;
            }
        } catch (\Exception $error) {
            return json_encode(['error' => $error->getMessage()]);
        }
        return json_encode(['error' => 'Что-то пошло не так!']);
    }

    /**
     * @param Request $request
     * @return string|void
     */
    public function create(Request $request)
    {
        if (!$request->user()->hasRole(['admin', 'super-admin'])) {
            return '{"error":"access denied"}';
        }
        $validated = self::validateInput($request);
        $validated_cities = self::validateInputCities(json_decode($request->only('cities')['cities'], true));
        if ($validated->fails()) {
            foreach ($validated->errors()->get('*') as $key => $error) {
                $message['errors'][$key] = implode($error);
            }
            return json_encode($message);
        }

        if ($validated_cities === false) {
            return '{"error":"Неопознанный город!"}';
        }

        if (($user = User::where('login', $validated->safe()->only('login'))->first()) !== null) {
            $user->update([
                'name' => ($validated->safe()->only('name') ?? $user->name),
                'login' => ($validated->safe()->only('login') ?? $user->login),
                'telegram_chat_id' => ($validated->safe()->only('telegramID') ?? $user->telegram_chat_id),
                'cities' => ($validated_cities !== true ? json_encode($validated_cities) : $user->cities)
            ]);
        }

        $validated = self::validateInput($request, true);
        $attributes = [
            'name' => $validated->safe()->only('name')['name'],
            'login' => $validated->safe()->only('login')['login'],
            'telegram_chat_id' => (int)$validated->safe()->only('telegramID')['telegramID'],
            'password' => self::generatePassword(),
            'cities' => json_encode($validated_cities),
        ];
        try {
            $new_user = User::create($attributes);
            foreach (json_decode($request->only('role')['role'],true) as $role){
                $new_user->assignRole($role);
            }
        }catch (\Exception $error){
            return json_encode(['error'=>$error->getMessage()]);
        }

        return $new_user->id;
    }

    /**
     * @param Request $request
     * @param $user
     * @return void
     */
    protected function authenticated(Request $request, $user)
    {
        $user->generateTwoFactorCode();
        TwoFactorCode::sendTelegramCode($user);
    }

    public function getUsers(){
        return User::all()->map(function (User $user){
            return [
                'id' => $user->id,
                'login' => $user->login,
                'name' => $user->name,
                'roles' => $user->getRoleNames(),
                'cities' => !empty($user->cities) ? json_decode($user->cities,true) : [],
                'birthtime' => $user->created_at,
                'edittime' => $user->updated_at,
                'telegramID' => $user->telegram_chat_id,
            ];
        });
    }

    /**
     * @return array
     */
    public function getRoles(): array{
        $collection = [];
        $roles = Role::all()->where('name', '!=','super-admin');
        foreach ($roles as $role){
            $collection[] = $role->name;
        }
        return $collection;
    }

    /**
     * @param bool $unique
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private static function validateInput(Request $request, bool $unique = false): \Illuminate\Validation\Validator
    {
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'login' => ['required', 'string', 'max:50'],
            'telegramID' => ['required', 'digits_between:5,15'],
        ];
        if ($unique) {
            $rules['name'][] = 'unique:users';
            $rules['telegramID'][] = 'unique:users,telegram_chat_id';
        }
        return Validator::make($request->all(), $rules);
    }

    /**
     * @param array $cities
     * @return array|bool
     */
    private static function validateInputCities(array $cities)
    {
        if (empty($cities)) {
            return true;
        }

        $cities_model = City::all('name')->keyBy('name')->toArray();

        foreach ($cities as $city) {
            if (!isset($cities_model[$city])) {
                return false;
            }
        }

        return $cities;
    }

    private static function generatePassword()
    {
        return Str::random(mt_rand(7, 50));
    }
}
