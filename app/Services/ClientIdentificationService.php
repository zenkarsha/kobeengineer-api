<?php

namespace App\Services;

use App\Repositories\ClientIdentificationRepository;

class ClientIdentificationService extends Service
{
    protected $repo;

    public function __construct(ClientIdentificationRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create()
    {
        $data = [
            'identification' => $this->generateIdentification(),
        ];
        $result = $this->repo->create($data);

        if ($result)
            return $this->successResponse('Ok', $data);
        else
            return $this->badRequestResponse('Create failed.');
    }

    public function updateForbidden($id, $value)
    {
        $value = (int) $value == 1 ? 1 : 0;

        return $this->repo->updateColumn($id, 'forbidden', $value);
    }

    private function generateIdentification()
    {
        do {
            $identification = randString(64);
        } while ($this->repo->countByIdentification($identification) > 0);

        return $identification;
    }
}

