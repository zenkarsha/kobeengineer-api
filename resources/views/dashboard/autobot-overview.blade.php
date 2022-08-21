@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-autobot-overview'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  <table class="ui celled definition table">
    <thead>
      <tr>
        <th class="ui center aligned">&nbsp;</th>
        <th class="ui center aligned">Bot</th>
        <th class="ui center aligned">Last Poke</th>
        <th class="ui center aligned">Actions</th>
      </tr>
    </thead>
    <tbody>
      @if (count($result) == 0)
        <tr>
          <td colspan="4">
            @lang('table.description-no_result')
          </td>
        </tr>
      @else
        @foreach ($result as $item)
          @include('partial.dashboard.overview.autobot-item')
        @endforeach
      @endif
    </tbody>
    <tfoot>
      <tr>
        <th colspan="4">
          @include('partial.dashboard.shared.overview-pagination')
        </th>
      </tr>
    </tfoot>
  </table>
@endsection

@section('foot')
  @include('partial.dashboard.script.autobot-overview')
@endsection
