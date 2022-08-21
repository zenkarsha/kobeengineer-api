<?php

namespace App\Presenters;

use Illuminate\Http\Request;
use App\Repositories\SettingRepository;
use Carbon\Carbon;
use Lang;

class DashboardPresenter
{
    protected $settingRepository;

    public function __construct(Request $request, SettingRepository $settingRepository)
    {
        $this->request = $request;
        $this->settingRepository = $settingRepository;
    }

    public function getSetting($key)
    {
        $result = $this->settingRepository->getItemByKey($key);
        return $result->value;
    }

    public function createSidenavItem($path, $title)
    {
        return '<a class="'.$this->getSidenavClassName($path).'item" href="'.__($path).'">'.Lang::get('page-title.'.$title).'</a>';
    }

    public function getSidenavClassName($path)
    {
        return $this->checkPathActivity($path) ? 'active yellow ' : '';
    }

    public function createPaginationItems($data)
    {
        $from = $data->currentPage() - 3;
        $to = $data->currentPage() + 3;

        if ($from < 1) {
            $to -= $from;
            $from = 1;
        }
        if ($to > $data->lastPage()) {
            $to = $data->lastPage();
            $from -= ($to - $data->lastPage());
            if ($from < 1)
                $from = 1;
        }

        $html = '';
        for ($i = $from; $i <= $to; $i++) {
            $active = $i == $data->currentPage() ? ' active' : '';
            $url = $i == $data->currentPage() ? '#' : $data->url($i);
            $html .= "<a class=\"item$active\" href=\"$url\">$i</a>";
        }

        return $html;
    }

    public function handleHtmlCharsEscape($html)
    {
        $html = str_replace("\r", "", $html);
        $html = str_replace("\n", '\n', $html);
        $html = str_replace("'", "\'", $html);
        $html = str_replace("/", "\/", $html);
        return $html;
    }

    private function checkPathActivity($path)
    {
        $current_path = '';
        $i = 1;
        do {
            $current_path .= '/' . $this->request->segment($i);
            $i++;
        } while (trim($this->request->segment($i)) != '');

        if (__($path) == $current_path)
            return true;

        return false;
    }

    public function secondToReadable($seconds)
    {
        $time = Carbon::now()->subSeconds($seconds)->diffForHumans();
        $time = str_replace(' ago', '', $time);

        return $time;
    }

    public function convertToSlider($value, $classname)
    {
        $checked = (int) $value == 1 ? ' checked' : '';
        $html = '<div class="ui fitted slider checkbox">
          <input type="checkbox" class="'.$classname.'"'.$checked.'>
          <label></label>
        </div>';

        return $html;
    }
}
