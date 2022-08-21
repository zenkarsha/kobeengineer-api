@inject('postPresenter', 'App\Presenters\PostPresenter')
@inject('userPresenter', 'App\Presenters\UserPresenter')

<div class="ui tiny {{ $postPresenter->getStateLabelColor($item) }} label">
  {{ $postPresenter->getStateText($item) }}
</div>
<div class="ui tiny label">
  {{ $postPresenter->getPostTypeText($item->type) }}
</div>
<div class="ui tiny label action-search-user{{ $postPresenter->getUserStateColor($item->user_id) }}">
  {{ $postPresenter->getUserName($item->user_id) }}
</div>

@if ($userPresenter->checkIpBanState($item->client_ip))
  <div class="ui tiny black label action-search-ip">{{ $item->client_ip }}</div>
@else
  <div class="ui tiny label action-search-ip">{{ $item->client_ip }}</div>
@endif

@if ($userPresenter->checkClientIdentificationBanState($item->client_identification))
  <div class="ui tiny black label action-search-client-identification">{{ $item->client_identification }}</div>
@else
  <div class="ui tiny label action-search-client-identification">{{ $item->client_identification }}</div>
@endif

@if ((int) $item->published > 0 || (int) $item->unpublished == 1)
  <div class="ui mini label">
    <i class="thumbs up icon"></i> {{ $item->fb_likes }}
  </div>
  <div class="ui mini label">
    <i class="comment icon"></i> {{ $item->fb_comments }}
  </div>
  <div class="ui mini label">
    <i class="external share icon"></i> {{ $item->fb_shares }}
  </div>
  <div class="ui mini label">
    <i class="warning circle icon"></i> {{ $item->report }}
  </div>
  <div class="ui mini label">
    <i class="send icon"></i> {{ $item->published_at }}
  </div>
@endif

