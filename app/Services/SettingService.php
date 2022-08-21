<?php

namespace App\Services;

use App\Repositories\SettingRepository;

class SettingService extends Service
{
    protected $repo;

    public function __construct(SettingRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create($form)
    {
        $result = $this->repo->create([
            'key' => $form['key'],
            'value' => $form['value'],
            'type' => $form['type'],
            'label' => $form['label'],
            'group' => $form['group'],
            'custom_config' => $form['custom_config'] != '' ? json_encode(json_decode($form['custom_config'])) : '',
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
            'key' => $form['key'],
            'value' => $form['value'],
            'type' => $form['type'],
            'label' => $form['label'],
            'group' => $form['group'],
            'custom_config' => $form['custom_config'] != '' ? json_encode(json_decode($form['custom_config'])) : '',
        ]);

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

    public function save($form)
    {
        $settings = $this->repo->getAll();
        foreach ($settings as $item) {
            if ($item->type == 'slider')
                $value = isset($form[$item->key]) ? 'on' : 'off';
            else
                $value = $form[$item->key];
            $this->repo->updateByKey($item->key, $value);
        }

        return $this->successResponse();
    }
}
