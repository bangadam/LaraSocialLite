<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\User;
use Socialite;

class SocialController extends Controller
{
    protected $redirectTo = '/home';

    public function redirectToProvider($provider) {
      // dd(Socialite::driver($provider)->redirect());
      return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider) {
      $user = Socialite::driver($provider)->user();
      // dd($user);
      $authUser = $this->findOrCreateUser($user, $provider);
      Auth::login($authUser, true);
      return redirect($this->redirectTo);

    }

    public function findOrCreateUser($user, $provider) {

      $authUser = User::where('provider_id', $user->id)->first();
      // dd()
      if ($authUser) {
        return $authUser;
      }
      // dd()
      return User::create([
        'name' => $user->name,
        'email' => $user->email,
        'provider' => $provider,
        'provider_id' => $user->id
      ]);

    }

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }
}
