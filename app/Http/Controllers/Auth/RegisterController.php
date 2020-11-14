<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\NoticeManagerController;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:16', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return   \App\User
     */
    public function create(Request $request)
    {
        $data['email'] = $request->get('email');
        $data['name'] = $request->get('name');
        $data['password'] = $request->get('password');
        $data['role'] = $request->get('role');
        $name = '';
        if(!empty(User::where('email', $request->get('email'))->get()->toArray())){
            return 'account exist';
        }else{
            $validator = Validator::make($request->all(), [
                'password' => ['required',
                    'min:8',
                    'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
                    ], //英數混合且至少8碼即可通過
            ]);

            if ($validator->fails()) {
                return 'validate fail';
            }else{
                $user = User::create([
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
            }
        }
        if(isset($data['name'])){
            $name = $data['name'];
        }
        if(isset($user)){
            $user->assignRole($data['role']);
            $user->update([
                'name' => $name,
            ]);

            Auth::loginUsingId($user->id);

            //通知系統觸發-註冊成功
            $notified_user = $user->toArray();
            $notice_manager = new NoticeManagerController();
            $admin_array = $notified_user;
            //TODO admin_array為角色陣列，要放入 User的Model，且轉成陣列型態(toArray())過後的，之後可針對角色去擴充
            $notice_manager->trigger_event_center('register', array('admin' => $admin_array));

            return $user;
        }else{
            return 'Create failed.';
        }

    }
}
