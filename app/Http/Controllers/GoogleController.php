<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    public function redirectToGoogle(){

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(){
        try {

            $user = Socialite::driver('google')->user();
            $findUser = User::where('google_id' , $user->id)->first();

            if($findUser){

                Auth::login($findUser);
                return redirect()->intended('dashboard');

            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => Hash::make('12345678'),
                ]);

                Auth::login($newUser);
                return redirect()->intended('dashboard');

            }

        } catch (Exception $e) {

            dd($e->getMessage());
        }
    }
}
