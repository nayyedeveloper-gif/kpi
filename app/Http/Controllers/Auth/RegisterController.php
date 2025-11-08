<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Determine role based on position
        $position = \App\Models\Position::find($data['position_id']);
        
        if (!$position) {
            throw new \Exception('Selected position not found.');
        }
        
        $roleId = $this->determineRoleId($position->name);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'department_id' => $data['department_id'],
            'position_id' => $data['position_id'],
            'role_id' => $roleId,
            'is_active' => true,
        ]);
    }

    /**
     * Determine user role ID based on position name.
     *
     * @param  string  $positionName
     * @return int
     */
    protected function determineRoleId($positionName)
    {
        $positionLower = strtolower($positionName);

        if (str_contains($positionLower, 'ceo') || str_contains($positionLower, 'chief executive') || str_contains($positionLower, 'md')) {
            $role = \App\Models\Role::where('name', 'admin')->first();
        } elseif (str_contains($positionLower, 'manager') || str_contains($positionLower, 'director')) {
            $role = \App\Models\Role::where('name', 'manager')->first();
        } elseif (str_contains($positionLower, 'supervisor')) {
            $role = \App\Models\Role::where('name', 'supervisor')->first();
        } else {
            $role = \App\Models\Role::where('name', 'employee')->first();
        }

        // Fallback to employee role if no role is found
        if (!$role) {
            $role = \App\Models\Role::where('name', 'employee')->first();
        }

        // If still no role, throw an exception
        if (!$role) {
            throw new \Exception('Employee role not found. Please seed the roles table.');
        }

        return $role->id;
    }
}
