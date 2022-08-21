@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
@inject('postPresenter', 'App\Presenters\PostPresenter')

<div class="ui stacked segment {{ $postPresenter->getPostStateColor($item) }} post-item" data-id="{{ $item->id }}" data-datas="{{ json_encode($item->toArray()) }}">
  <h3 class="ui header">
    <span class="button-show-post-detail" style="cursor: pointer;">
      #{{ $item->id }}
      @if ($item->true_id != '')
      #{{ $dashboardPresenter->getSetting('facebook_page_name') }}{{ $item->true_id }}
      @endif
    </span>
    @include('partial.dashboard.overview.post-action-dropdown')
    <div class="sub header">
      <small>{{ $item->created_at }}</small>
    </div>
  </h3>
  <div class="post-content">
    @if ($item->reply_to != '')
      RE: #{{ $dashboardPresenter->getSetting('facebook_page_name') }}{{ $item->reply_to }}<br />
    @endif

    @if ($item->type == 3)
      @if ($media = $postPresenter->getPostMedia($item->id))
        <div class="ui inverted segment">
          <img class="ui fluid centered image" src="{{ $media }}">
        </div>
      @else
        <div class="ui inverted segment">
          [img] {!! nl2br(htmlentities($item->content)) !!}
        </div>
      @endif
    @else
      {!! nl2br(htmlentities($item->content)) !!}
    @endif

    @if (!empty($item->hashtag) || $item->link != '')
      <br />
      @if (!empty($item->hashtag))
        <code>{{ $item->hashtag }}</code>
      @endif
      @if ($item->link)
        <div class="ui ignored message">
          <a href="{{ $item->link }}" target="_blank">
            <code>{{ $item->link }}</code>
          </a>
        </div>
      @endif
    @endif

    @if ((int) $item->type == 4)
      <div class="ui ignored info message">
        <pre>{{ $postPresenter->getPostCode($item->id) }}</pre>
      </div>
    @endif
  </div>
  @if (isset($pending_mode) && $pending_mode)
    @include('partial.dashboard.overview.post-pending-buttons')
  @endif
  @if (!isset($pending_mode))
    <div class="post-labels">
      @include('partial.dashboard.overview.post-labels')
    </div>
  @endif
</div>
