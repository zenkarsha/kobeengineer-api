@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')

<div class="ui right floated pagination menu">
  <a class="icon item{{ $result->currentPage() == 1 ? ' disabled' : '' }}" href="{{ $result->previousPageUrl() }}">
    <i class="left chevron icon"></i>
  </a>
  {!! $dashboardPresenter->createPaginationItems($result) !!}
  <a class="icon item{{ $result->currentPage() == $result->lastPage() ? ' disabled' : '' }}" href="{{ $result->nextPageUrl() }}">
    <i class="right chevron icon"></i>
  </a>
</div>
