<table class="ui celled table">
  <thead>
    <tr>
      @foreach ($columns as $key => $config)
        <th class="ui center aligned">{{ $config['title'] }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @if (count($data) == 0)
      <tr>
        <td colspan="{{ count($columns) }}">
          @lang('overview-table.description-no_result')
        </td>
      </tr>
    @else
      @foreach ($data as $item)
        <tr data-id="{{ $item->id }}" data-datas="{{ json_encode($item->toArray()) }}">
          @foreach($columns as $key => $config)
            @if ($key == 'actions')
              <td class="ui center aligned">
                @foreach ($actions as $action)
                  {!! $action !!}
                @endforeach
              </td>
            @else
              <td class="{{ array_key_exists('classname', $config) ? $config['classname'] : (array_key_exists('classname_presenter', $config) ? call_user_func_array([$config['classname_presenter'][0], $config['classname_presenter'][1]], [$item->$key]) : '') }}">
                @if (array_key_exists('content_presenter', $config))
                  {!!
  call_user_func_array([$config['content_presenter'][0], $config['content_presenter'][1]], (isset($config['content_presenter'][2]) ? array_merge([$item->$key], ($config['content_presenter'][2] == ['self'] ? [$item] : $config['content_presenter'][2])) : [$item->$key])) !!}
                @else
                  {{ $item->$key }}
                @endif
              </th>
            @endif
          @endforeach
        </tr>
      @endforeach
    @endif
  </tbody>
  <tfoot>
    <tr>
      <th colspan="{{ count($columns) }}">
        <div class="ui right floated pagination menu">
          <a class="icon item{{ $data->currentPage() == 1 ? ' disabled' : '' }}" href="{{ $data->previousPageUrl() }}">
            <i class="left chevron icon"></i>
          </a>
          {!! $dashboardPresenter->createPaginationItems($data) !!}
          <a class="icon item{{ $data->currentPage() == $data->lastPage() ? ' disabled' : '' }}" href="{{ $data->nextPageUrl() }}">
            <i class="right chevron icon"></i>
          </a>
        </div>
      </th>
    </tr>
  </tfoot>
</table>
