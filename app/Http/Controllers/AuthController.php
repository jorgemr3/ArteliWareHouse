<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validaciones
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);

            $user->save();
            DB::commit();

            Auth::login($user);

            return redirect(route('index'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Hubo un problema al registrar el usuario. Por favor, intente nuevamente.')->withInput();
        }
    }



    public function login(Request $request)
    {
        // Validación de credenciales
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('index'));
        } else {
            return redirect(route('login'))->withErrors([
                'login_error' => 'Credenciales incorrectas, por favor intente nuevamente.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }

    public function indexLogin()
    {
        return view("auth.login");
    }

    public function indexRegister()
    {
        return view("auth.register");
    }
}
