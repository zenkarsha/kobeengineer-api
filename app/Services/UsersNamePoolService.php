<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\UsersNamePoolRepository;

class UsersNamePoolService extends Service
{
    const mDebug = False;

    const TYPE_COMPANY = "COMPANY";
    const TYPE_ANIMAL = "ANIMAL";

    protected $mUserRepository;
    protected $mUserNameRepository;

    public function __construct(UserRepository $aUserRepository, UsersNamePoolRepository $aUserNameRepository)
    {
        $this->mUserRepository = $aUserRepository;
        $this->mUserNameRepository = $aUserNameRepository;
    }

    public function create($form)
    {
        $result = False;
        // key exist
        $name = array_key_exists('name', $form) ? $form['name'] : NULL;
        $type = array_key_exists('type', $form) ? $form['type'] : NULL;

        // input exist
        if (isset($name) && isset($type)) {
            $result = $this->mUserNameRepository->create([
                'name' => $name,
                'type' => $type,
            ]);
        }

        if ($result)
            return $this->successResponse();
        else
            return $this->badRequestResponse('Create failed.');
    }

    public function update($form)
    {
        $result = False;
        // key exist
        $id = array_key_exists('id', $form) ? (int) $form['id'] : NULL;
        $name = array_key_exists('name', $form) ? $form['name'] : NULL;
        $type = array_key_exists('type', $form) ? $form['type'] : NULL;

        // input exist
        if (isset($id) && isset($name) && isset($type)) {
            $result = $this->mUserNameRepository->update($id, [
                'id' => $id,
                'name' => $name,
                'type' => $type,
            ]);
        }

        if ($result)
            return $this->successResponse();
        else
            return $this->badRequestResponse('Update failed.');
    }

    public function delete($id)
    {
        if ($this->repo->delete($id))
            return $this->successResponse();
        else
            return $this->badRequestResponse('Delete failed.');
    }

    public function generateRandomName() {
        while (True) {
            //generate the random user name
            $name = array(UsersNamePoolService::TYPE_COMPANY  => '',
                          UsersNamePoolService::TYPE_ANIMAL   => '');

            $username = $this->mUserNameRepository->getRandomGroupItem();
            foreach ($username as $key => $item) {
                $name[$item['type']] = $item['name'];
            }
            $caseType = (boolean) rand(0, 1);
            $nameString  = ucfirst(strtolower($name[UsersNamePoolService::TYPE_COMPANY]));
            $nameString .= $caseType ? '_' : '';
            $nameString .= $name[UsersNamePoolService::TYPE_ANIMAL];
            $nameString = $caseType ? strtolower($nameString) : $nameString;

            //query from user and check the name is exist
            $user = $this->mUserRepository->getItemByName($nameString);
            $this->debug($user);
            if (!$user) {
                break;
            }
        }
        $this->debug($nameString);
        return $nameString;
    }

    private function debug($aMessage) {
        if (UsersNamePoolService::mDebug) {
            dump($aMessage);
        }
    }
}
