<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Auth;

class APIUserService extends Service
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->user = Auth::user();
    }

    public function update($form)
    {
        $public = (boolean) $form['public'] ? 1 : 0;

        $result = $this->userRepository->update($this->user->id, [
            'public' => $public
        ]);

        if ($result)
            return $this->successResponse();
        else
            return $this->badRequestResponse('Update failed.');
    }
}
