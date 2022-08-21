@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
@inject('userPresenter', 'App\Presenters\UserPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-user-overview'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  @include('partial.dashboard.form.user-search-form')
  <table class="ui celled table">
    <thead>
      <tr>
        <th class="ui center aligned">#</th>
        <th class="ui center aligned">Name</th>
        <th class="ui center aligned">Posts</th>
        <th class="ui center aligned">Public</th>
        <th class="ui center aligned">Flagged</th>
        <th class="ui center aligned">Banned</th>
      </tr>
    </thead>
    <tbody>
      @if (count($result) == 0)
        <tr>
          <td colspan="6">
            @lang('table.description-no_result')
          </td>
        </tr>
      @else
        @foreach ($result as $item)
          <tr data-id="{{ $item->id }}" data-datas="{{ json_encode($item->toArray()) }}">
            <td class="ui center aligned collapsing">{{ $item->id }}</td>
            <td>
              {{-- TODO: click and search user's posts --}}
              <b>
                <a href="{{ __('/post/search/?keyword=user:' . $item->id) }}">
                  {{ $item->name }}
                </a>
              </b>
              <div class="ui mini label">
                {{ $item->provider }}
              </div>
              @if ((int) $item->verified == 1)
                <div class="ui mini green label">
                  Verified
                </div>
              @endif
            </td>
            <td class="ui center aligned">
              {{ $userPresenter->getUserPostCount($item->id) }}
            </td>
            <td class="ui center aligned">
              {{ $userPresenter->getPublicText($item->public) }}
            </td>
            <td class="collapsing">
              <div class="ui fitted slider checkbox">
                {!! $dashboardPresenter->convertToSlider($item->flagged, 'flag-slider') !!}
              </div>
            </td>
            <td class="collapsing">
              <div class="ui fitted slider checkbox">
                {!! $dashboardPresenter->convertToSlider($item->banned, 'ban-slider') !!}
              </div>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
    <tfoot>
      <tr>
        <th colspan="6">
          @include('partial.dashboard.shared.overview-pagination')
        </th>
      </tr>
    </tfoot>
  </table>
@endsection

@section('foot')
  @include('partial.dashboard.script.user-overview')
@endsection
