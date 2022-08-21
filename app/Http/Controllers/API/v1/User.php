<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\APICore;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\APIUserService;
use App\Services\APIUserPostService;
use Auth;
use Cookie;

/**
 * @resource User
 */
class User extends APICore
{
    protected $APIUserService;
    protected $APIUserPostService;

    public function __construct(APIUserService $APIUserService, APIUserPostService $APIUserPostService)
    {
        parent::__construct();

        $this->APIUserService = $APIUserService;
        $this->APIUserPostService = $APIUserPostService;
    }

    public function me()
    {
        $user = Auth::user();

        $user_data = [
            'id' => $user->id,
            'name' => $user->name,
            'provider' => $user->provider,
            'public' => $user->public,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        if ((int) $user->public == 1) {
            $user_data['personal_page'] = '/author/?u=' . $user->name;
            # TODO: add follower table and count
        }

        if ((int) $user->flagged == 1) $user_data['flagged'] = true;
        if ((int) $user->banned == 1) $user_data['banned'] = true;
        $user_data['verified'] = (int) $user->verified == 1 ? true : false;

        $response = [
            'success' => true,
            'status' => 'connected',
            'user' => $user_data,
        ];

        return response()->json($response);
    }

    public function getUserPosts(Request $request)
    {
        $current_page = $this->getCurrentPage($request);

        # TODO: filter script here

        $response = $this->APIUserPostService->getUserPosts($current_page);
        return response()->json($response);
    }

    public function deleteUserPost(Requests\PostDeleteRequest $request)
    {
        $response = $this->APIUserPostService->deleteUserPost($request->get('key'));
        return response()->json($response);
    }

    public function updateSetting(Requests\UserUpdateRequest $request)
    {
        $response = $this->APIUserService->update($request->all());
        return response()->json($response);
    }
}
