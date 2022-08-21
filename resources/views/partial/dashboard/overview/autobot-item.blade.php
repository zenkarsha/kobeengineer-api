@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')

<tr data-id="{{ $item->id }}" data-datas="{{ json_encode($item->toArray()) }}">
  <td class="collapsing">
    <div class="ui fitted slider checkbox">
      {!! $dashboardPresenter->convertToSlider($item->booting, 'boot-slider') !!}
    </div>
  </td>
  <td>
    <h4 class="ui header">
      {{ $item->name }}
      <small>
        (every {{ $dashboardPresenter->secondToReadable($item->frequency) }})
      </small>
    </h4>
    <small>
      Job: {{ $item->job }}
    </small><br />
    <small>
      Current session: <span class="session">{{ $item->session }}</span>
    </small>
  </td>
  <td class="ui center aligned collapsing">
    {{ $item->last_poked_at }}
  </td>
  <td class="ui center aligned collapsing">
    <button type="button" class="ui small button button-reboot">
      Reboot
    </button>
    <button type="button" class="ui small button button-edit">
      @lang('form.button-edit')
    </button>
    <button type="button" class="ui small button button-delete">
      @lang('form.button-delete')
    </button>
  </td>
</tr>
