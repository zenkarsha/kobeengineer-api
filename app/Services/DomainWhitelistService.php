<?php

namespace App\Services;

use App\Repositories\DomainWhitelistRepository;

class DomainWhitelistService extends Service
{
    protected $repo;

    public function __construct(DomainWhitelistRepository $repo)
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

