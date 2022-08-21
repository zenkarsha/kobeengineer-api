@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-setting-client-identification'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  <table class="ui unstackable celled table">
    <thead>
      <tr>
        <th class="ui center aligned">#</th>
        <th class="ui center aligned">Identification</th>
        <th class="ui center aligned">Forbidden</th>
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
            <td class="ui center aligned collapsing">
              {{ $item->id }}
            </td>
            <td>
              <b>
                <a href="{{ __('/post/search?keyword=ci:' . $item->id) }}">
                  {{ $item->identification }}
                </a>
              </b>
            </td>
            <td class="ui center aligned collapsing">
              <div class="ui fitted slider checkbox">
                <input type="checkbox" class="button-forbidden-slider"{{ $item->forbidden == 1 ? ' checked' : ''}}> <label></label>
              </div>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
    <tfoot>
      <tr>
        <th colspan="3">
          @include('partial.dashboard.shared.overview-pagination')
        </th>
      </tr>
    </tfoot>
  </table>
@endsection

@section('foot')
  @include('partial.dashboard.script.setting-client-identification')
@endsection
