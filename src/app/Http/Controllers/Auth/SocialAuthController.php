<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Auth;
use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class SocialAuthController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/';
    public function redirectToProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('twitter')->user();
        } catch (Exception $e) {
            return redirect('auth/twitter');
        }

        $authUser = $this->findOrCreateUser($user);

        Auth::login($authUser, true);

        return redirect('/');
    }
    private function findOrCreateUser($twitterUser)
    {
        $authUser = User::where('twitter_id', $twitterUser->id)->first();

        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'name' => $twitterUser->name,
            'email' => $twitterUser->email,
            'twitter_id' => $twitterUser->id,
        ]);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
