@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
@inject('postPresenter', 'App\Presenters\PostPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-post-overview'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
  @include('partial.dashboard.style.post-overview')
@endsection

@section('page-title-buttons')
  <a href="{{ __('/post/create') }}" class="ui button">
    <i class="plus icon"></i>
    @lang('page-title.dashboard-post-create')
  </a>
@endsection

@section('content')
  @include('partial.dashboard.form.post-search-form')
  <table class="ui very basic unstackable table">
    <tbody>
      @if (count($result) == 0)
        <tr>
          <td>
            @lang('table.description-no_result')
          </td>
        </tr>
      @else
        <tr>
          <td>
            @foreach ($result as $item)
              @include('partial.dashboard.overview.post-card')
            @endforeach
          </td>
        </tr>
      @endif
    </tbody>
    <tfoot>
      <tr>
        <th>
          @include('partial.dashboard.shared.overview-pagination')
        </th>
      </tr>
    </tfoot>
  </table>
  @include('partial.dashboard.modal.post-detail')
@endsection

@section('foot')
  <script src="/vendor/readmore.js-2.2.0/readmore.min.js"></script>
  @include('partial.dashboard.script.post-overview')
@endsection
