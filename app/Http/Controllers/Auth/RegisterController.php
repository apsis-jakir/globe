<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Form;

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

    //use RegistersUsers;
    public function showRegistrationForm()
    {
        $user_settigns = DB::table('user_settings')->get();
        $users = User::where('id', '!=', Auth::id())->get();
        $departments = DB::table('departments')->get();
        $desinations = DB::table('designations')->get();
        $allmodules = DB::table('modules')->get();
        $user_levels = DB::table('user_levels')->get();
        return view('auth.register', compact('users', 'departments', 'desinations', 'allmodules', 'user_levels', 'user_settigns'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/users';
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function users()
    {
        $routes = json_decode(Session::get('routes_list'),true);
        $dbid = getHouseFromThisRoutes($routes);
        $users = User::select('users.*','routes.id as route_id','zones.zone_name as zname','regions.region_name as rname','territories.territory_name as tname','distribution_houses.point_name as dname')
            ->leftJoin('zones','zones.id','=','users.zones_id')
            ->leftJoin('regions','regions.id','=','users.regions_id')
            ->leftJoin('territories','territories.id','=','users.territories_id')
            ->leftJoin('distribution_houses','distribution_houses.id','=','users.distribution_house_id')
            ->leftJoin('routes','routes.so_aso_user_id','=','users.id')
            ->whereIn('distribution_houses.id',$dbid)
            ->paginate(10);

        return view('auth.index', compact('users'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $userconfig = Config::get('constants.users_options');
        if ($userconfig['passwordtype'] == 'easy')
            $password_length = 'digits_between:8,12';

        if ($userconfig['passwordtype'] == 'normal')
            $password_length = 'digits_between:12,20';

        if ($userconfig['passwordtype'] == 'complex')
            $password_length = 'digits_between:12,20';

        $username = 'required|string|email|min:' . $userconfig['usernameminlength'] . '|max:' . $userconfig['usernamemaxlength'] . '|unique:users';
        $password = 'bail|required|string|confirmed|' . $password_length . '|password_' . $userconfig['passwordtype'];

        return Validator::make($data, [
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile' => 'required|numeric|unique:users',
            'date_of_birth' => 'required',
            'email' => $username,
            'username' => 'required',
            'password' => $password,
            'location_id' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'default_module_id' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $userconfig = Config::get('constants.users_options');
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'password_key' => md5($data['password']),
            'password_expire_days' => $userconfig['passwordexpireduration'],
            'username' => $data['username'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'mobile' => $data['mobile'],
            'date_of_birth' => Carbon::parse($data['date_of_birth'])->format('Y-m-d H:i:s'),
            'location_id' => $data['location_id'],
            'department_id' => $data['department_id'],
            'line_manager_id' => $data['line_manager_id'],
            'designation_id' => $data['designation_id'],
            'default_module_id' => $data['default_module_id'],
            'created_by' => Auth::id(),
            'status' => $data['status'],
        ]);


        if (count($data['user_module_id']) > 0) {
            foreach ($data['user_module_id'] as $mval) {
                if ($mval > 0) {
                    DB::table('privilege_modules')->insert(
                        [
                            'users_id' => $user->id,
                            'modules_id' => $mval,
                            'created_at' => Carbon::today()
                        ]
                    );
                }
            }
        }

        if (count($data['user_level_id']) > 0) {
            foreach ($data['user_level_id'] as $lval) {
                if ($lval > 0) {
                    DB::table('privilege_levels')->insert(
                        [
                            'users_id' => $user->id,
                            'user_levels_id' => $lval,
                            'created_at' => Carbon::today()
                        ]
                    );
                }
            }

        }

        return $user;
    }


    /**
     * Display the specified post.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('auth.show', compact('user'));
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $user_settigns = DB::table('user_settings')->get();
        $users = User::where('id', '!=', Auth::id())->get();
        $departments = DB::table('departments')->get();
        $designations = DB::table('designations')->get();
        $allmodules = DB::table('modules')->get();
        $user_levels = DB::table('user_levels')->get();
        $user = User::findOrFail($id);

        return view('auth.edit', compact('user', 'users', 'departments', 'designations', 'allmodules', 'user_levels', 'user_settigns'));
    }


    /**
     * Update the specified post in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $user = User::findOrFail($id);
            $user->update($data);

            return redirect()->route('auth.index')
                ->with('success_message', 'User was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    protected function getData(Request $request)
    {
        $userconfig = Config::get('constants.users_options');
        $username = 'required|string|email|min:' . $userconfig['usernameminlength'] . '|max:' . $userconfig['usernamemaxlength'] . '|unique:users';
        $password = 'bail|string|confirmed|password_' . $userconfig['passwordtype'];


        $rules = [
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile' => 'required|numeric|unique:users',
            'date_of_birth' => 'required',
            'email' => $username,
            'username' => 'required',
            'password' => $password,
            'location_id' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'default_module_id' => 'required',

        ];

        $data = $request->validate($rules);


        return $data;
    }


    public function profileForm()
    {
        $profileData = DB::table('users')
            ->select('users.id', 'users.name', 'users.email', 'users.code', 'locations.name as location', 'designations.name as designation')
            ->leftJoin('locations', 'users.location_id', '=', 'locations.id')
            ->leftJoin('designations', 'users.designation_id', '=', 'designations.id')
            ->where('users.id', '=', Auth::Id())
            ->get();
        return view('auth.profileForm', compact('profileData'));
    }

    public function changepassword(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Password changed successfully !");
    }

}
