<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Services\UsersNamePoolService;
use Session;
use Auth;
use Socialite;
use JWTAuth;
use Cookie;

class AuthController extends Controller
{
    public function __construct(UsersNamePoolService $usersNamePoolService)
    {
        $this->usersNamePoolService = $usersNamePoolService;
    }

    public function redirectToProvider($provider)
    {
        Session::flash('redirect_url', \Request::server('HTTP_REFERER'));

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return Redirect::to('/auth/' . $provider);
        }

        $auth_user = $this->findOrCreateUser($user, $provider);

        Auth::login($auth_user, true);

        $token = JWTAuth::fromUser($auth_user);
        $cookie = Cookie::make('_ggid', $token, 60 * 24 * 60, null, null, false, false);

        return Redirect::to(Session::get('redirect_url'))->withCookie($cookie);
    }

    public function findOrCreateUser($user, $provider)
    {
        $auth_user = User::where('provider_id', $user->id)->first();

        if ($auth_user) {
            if ($auth_user->verified == 0 && ($provider == 'github' || $provider == 'bitbucket')) {
                if ($provider == 'github')
                    $verified = $this->verifyGithubUser($user->user);
                elseif ($provider == 'bitbucket')
                    $verified = $this->verifyBitbucketUser($user->user);

                if ($auth_user->verified != $verified) {
                    $auth_user->verified = $verified;
                    $auth_user->save();
                }
            }

            return $auth_user;
        }

        $verified = 0;
        if ($provider == 'github')
            $verified = $this->verifyGithubUser($user->user);
        elseif ($provider == 'bitbucket')
            $verified = $this->verifyBitbucketUser($user->user);

        if ($provider == 'github' || $provider == 'bitbucket')
            $name = $this->usersNamePoolService->generateRandomName();
        else
            $name = $user->name;

        return User::create([
            'name'     => $name,
            'provider' => $provider,
            'provider_id' => $user->id,
            'verified' => $verified,
        ]);
    }

    private function verifyGithubUser($user)
    {
        $verified = 0;

        if (diffTimesInMinutes($user['created_at'], currentTime()) > 60 * 24 * 15) {
            // if ((int) $user['public_repos'] > 0)
            // {
            //     if (isset($user['repos_url'])) {
            //         try {
            //             $result = json_decode(httpRequest($user['repos_url']));
            //             foreach ($result as $repo) {
            //                 if ((int) $repo->size > 0)
            //                     $verified = 1;
            //             }
            //         } catch (\Exception $e) {
            //             \Log::error($e->getMessage());
            //         }
            //     }
            // }
            $verified = 1;
        }

        return $verified;
    }

    private function verifyBitbucketUser($user)
    {
        $verified = 0;

        if (count($user['repositories']) > 0) {
            foreach ($user['repositories'] as $repo) {
                if ((int) $repo['size'] > 33348 && diffTimesInMinutes($repo['created_on'], currentTime()) > 60 * 24 * 15)
                    $verified = 1;
            }
        }

        return $verified;
    }
}
