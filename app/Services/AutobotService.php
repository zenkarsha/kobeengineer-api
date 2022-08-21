<?php

namespace App\Services;

use App\Repositories\AutobotRepository;
use Queue;
use URL;

class AutobotService extends Service
{
    protected $repo;

    public function __construct(AutobotRepository $repo)
    {
        $this->repo = $repo;
    }

    public function boot($id, $value)
    {
        $result = $this->repo->getItem($id);

        if (count($result))
        {
            $this->repo->updateBoot($id, $value);

            if ($value == 1)
                if ($response = $this->updateSession($id))
                    $this->pokeSelf($id);

            return $this->successResponse();
        }
        else
            return $this->badRequestResponse('Autobot not exist.');
    }

    public function reboot($id)
    {
        $result = $this->repo->getItem($id);

        if (count($result))
        {
            if ($response = $this->updateSession($id))
                $this->pokeSelf($id);

            return $response;
        }

        return $this->badRequestResponse('Reboot failed.');
    }

    public function poke($id, $session, $access_token)
    {
        $result = $this->repo->getItem($id);

        if ($result->booting == 1 && $result->session == $session && $result->access_token == $access_token)
        {
            Queue::push($result->job);

            $this->repo->update($id, [
                'last_poked_at' => currentTime(),
            ]);

            $this->pokeSelf($id);

            return $this->successResponse();
        }
        else
            return $this->badRequestResponse('Nothing happend.');
    }

    public function create($form)
    {
        $result = $this->repo->create([
            'name' => $form['name'],
            'access_token' => $form['access_token'],
            'session' => randString(),
            'job' => $form['job'],
            'frequency' => (int) $form['frequency'],
        ]);

        if ($result)
            return $this->successResponse();
        else
            return $this->badRequestResponse('Create failed.');
    }

    public function update($form)
    {
        $id = (int) $form['id'];

        $result = $this->repo->update($id, [
            'name' => $form['name'],
            'access_token' => $form['access_token'],
            'job' => $form['job'],
            'frequency' => (int) $form['frequency'],
        ]);

        if ($result)
            return $this->successResponse();
        else
            return $this->badRequestResponse('Update failed.');
    }

    public function updateSession($id)
    {
        $data = [
            'session' => randString(),
        ];
        $result = $this->repo->update($id, $data);

        if ($result)
            return $this->successResponse('Session refresh.', $data);
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

    private function pokeSelf($id)
    {
        $result = $this->repo->getItem($id);

        $data = [
            'url' => $this->getPokeUrl($id),
            'delay' => $this->countPokeDelay($result),
        ];

        Queue::push('App\Jobs\Autobot@poke', $data);
    }

    private function getPokeUrl($id)
    {
        $result = $this->repo->getItem($id);
        $url = URL::to('/autobot/poke/' . $result->id) . '?session=' . $result->session . '&access_token=' . $result->access_token;

        return $url;
    }

    private function countPokeDelay($bot)
    {
        $frequency = $bot->frequency;

        return (int) $frequency - (time() % (int) $frequency);
    }
}
