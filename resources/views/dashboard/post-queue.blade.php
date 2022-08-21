@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
@inject('publisherQueuePresenter', 'App\Presenters\PublisherQueuePresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-post-queue'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  <table class="ui celled table">
    <thead>
      <tr>
        <th class="ui center aligned">ID</th>
        <th class="ui center aligned">Post</th>
        <th class="ui center aligned">Created at</th>
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
          <tr data-id="{{ $item->id }}" data-datas="{{ json_encode($item->toArray()) }}">
            <td class="ui center aligned collapsing">
              {{ $item->post_id }}
            </td>
            <td>
              <div class="ui tiny label">
                {{ $publisherQueuePresenter->getPostType($item->post_id) }}
              </div>
              {{ $publisherQueuePresenter->getPostContent($item->post_id) }}
            </td>
            <td class="ui center aligned collapsing">
              {{ $item->created_at }}
            </td>
            <td class="ui center aligned collapsing">
              <button type="button" class="ui small button button-delete">
                @lang('form.button-delete')
              </button>
            </td>
          </tr>
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
  @include('partial.dashboard.script.post-queue')
@endsection
