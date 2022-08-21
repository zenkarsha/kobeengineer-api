@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-setting-ip-blacklist'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  <table class="ui unstackable celled table">
    <thead>
      <tr>
        <th class="ui center aligned">IP</th>
        <th class="ui center aligned">Forbidden</th>
        <th class="ui center aligned">Actions</th>
      </tr>
    </thead>
    <tbody>
      @if (count($result) == 0)
        <tr>
          <td colspan="3">
            @lang('table.description-no_result')
          </td>
        </tr>
      @else
        @foreach ($result as $item)
          <tr data-id="{{ $item->id }}" data-datas="{{ json_encode($item->toArray()) }}">
            <td class="ui center aligned">
              <b>{{ $item->ip }}</b>
            </td>
            <td class="ui center aligned collapsing">
              <div class="ui fitted slider checkbox">
                <input type="checkbox" class="button-forbidden-slider"{{ $item->forbidden == 1 ? ' checked' : ''}}> <label></label>
              </div>
            </td>
            <td class="ui center aligned collapsing">
              <button class="ui labeled icon button button-delete">
                <i class="trash icon"></i>
                @lang('table.button-delete')
              </button>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
@endsection

@section('foot')
  @include('partial.dashboard.script.setting-ip-blacklist')
@endsection
