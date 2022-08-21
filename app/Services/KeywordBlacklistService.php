<?php

namespace App\Services;

use App\Repositories\KeywordBlacklistRepository;

class KeywordBlacklistService extends Service
{
    protected $repo;

    public function __construct(KeywordBlacklistRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create($form)
    {
        $result = $this->repo->create([
            'keyword' => $form['keyword'],
            'forbidden' => (int) $form['forbidden'],
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

    public function updateForbidden($id, $value)
    {
        $value = (int) $value == 1 ? 1 : 0;

        return $this->repo->updateColumn($id, 'forbidden', $value);
    }
}
