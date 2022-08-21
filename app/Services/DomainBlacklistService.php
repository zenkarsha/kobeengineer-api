<?php

namespace App\Services;

use App\Repositories\DomainBlacklistRepository;

class DomainBlacklistService extends Service
{
    protected $repo;

    public function __construct(DomainBlacklistRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create($form)
    {
        $result = $this->repo->create([
            'domain' => $form['domain'],
        ]);

        if ($result)
            return $this->successResponse();
        else
            return $this->badRequestResponse('Create failed.');
    }

    public function delete($id)
    {
        if ($this->repo->delete($id))
            return $this->successResponse();
        else
            return $this->badRequestResponse('Delete failed.');
    }
}

