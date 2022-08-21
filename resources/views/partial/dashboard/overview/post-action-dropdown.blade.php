@inject('postPresenter', 'App\Presenters\PostPresenter')
@inject('userPresenter', 'App\Presenters\UserPresenter')

<div class="ui dropdown">
  <i class="small dropdown icon"></i>
  <div class="menu">
    @if ((int) $item->published > 0)
      <div class="item action-unpublish">@lang('post.action-unpublish')</div>
      <div class="ui divider"></div>
      <div class="item">
        <i class="dropdown icon"></i>
        <span class="text">@lang('post.label-post_link')</span>
        <div class="menu">
          <a href="https://example.com/post/?id={{ $item->true_id }}" target="_blank" class="item">
            <i class="code icon"></i> Official
          </a>
          <?php
            $post_links = $postPresenter->getPublishedLinks($item->id);
          ?>
          @foreach ($post_links as $link)
            @if ($link->type == 'github')
              <a href="{{ $link->url }}" target="_blank" class="item">
                <i class="github icon"></i> Github
              </a>
            @elseif ($link->type == 'facebook')
              <a href="{{ $link->url }}" target="_blank" class="item">
                <i class="facebook icon"></i> Facebook
              </a>
            @elseif ($link->type == 'twitter')
              <a href="{{ $link->url }}" target="_blank" class="item">
                <i class="twitter icon"></i> Twitter
              </a>
            @endif
          @endforeach
        </div>
      </div>
    @elseif ((int) $item->pending == 1)
      <div class="item action-allow">@lang('post.action-allow')</div>
      <div class="item action-deny">@lang('post.action-deny')</div>
    @elseif ((int) $item->queuing == 1)
      <div class="item action-cancel_queuing">@lang('post.action-cancel_queuing')</div>
      @if ((int) $item->priority == 0)
        <div class="item action-set_priority">@lang('post.action-set_priority')</div>
      @endif
    @elseif ((int) $item->denied == 1)
      <div class="item action-allow">@lang('post.action-allow')</div>
    @elseif ((int) $item->unpublished == 1)
      <div class="item action-republish">@lang('post.action-republish')</div>
    @elseif ($postPresenter->countPublishFailed($item->id) > 0)
      <div class="item action-republish">@lang('post.action-republish')</div>
    @elseif ((int) $item->analysed == 0)
      <div class="item action-allow">@lang('post.action-allow')</div>
      <div class="item action-deny">@lang('post.action-deny')</div>
    @endif

    @if ((int) $item->type == 3 || (int) $item->type == 4)
      <div class="item">
        <i class="dropdown icon"></i>
        <span class="text">@lang('post.label-post_image_link')</span>
        <div class="menu">
          <a href="/v1/post/image/{{ $item->key }}?query_token={{ $item->query_token }}" target="_blank" class="item">
            @lang('post.label-post_image_link_official')
          </a>
          <?php
            $post_media_links = $postPresenter->getPostMediaLinks($item->id);
          ?>
          @foreach ($post_media_links as $link)
            @if ($link->type == 'github')
              <a href="{{ $link->url }}" target="_blank" class="item">
                @lang('post.label-post_image_link_github')
              </a>
            @elseif ($link->type == 'imgur')
              <a href="{{ $link->url }}" target="_blank" class="item">
                @lang('post.label-post_image_link_imgur')
              </a>
            @elseif ($link->type == 'twitter')
              <a href="{{ $link->url }}" target="_blank" class="item">
                @lang('post.label-post_image_link_twitter')
              </a>
            @endif
          @endforeach
        </div>
      </div>
    @endif

    <div class="ui divider"></div>
    <div class="item">
      <i class="dropdown icon"></i>
      <span class="text">@lang('post.action-ban')</span>
      <div class="menu">
        <div class="item action-ban_all">@lang('post.action-ban_all')</div>
        <div class="item action-unban_all">@lang('post.action-unban_all')</div>
        <div class="ui divider"></div>
        @if ($userPresenter->checkUserFlagState($item->user_id))
          <div class="item action-unflag_user">@lang('post.action-unflag_user')</div>
        @else
          <div class="item action-flag_user">@lang('post.action-flag_user')</div>
        @endif
        @if ($userPresenter->checkUserBanState($item->user_id))
          <div class="item action-unban_user">@lang('post.action-unban_user')</div>
        @else
          <div class="item action-ban_user">@lang('post.action-ban_user')</div>
        @endif
        @if ($userPresenter->checkIpBanState($item->client_ip))
          <div class="item action-unban_ip">@lang('post.action-unban_ip')</div>
        @else
          <div class="item action-ban_ip">@lang('post.action-ban_ip')</div>
        @endif
        @if ($userPresenter->checkClientIdentificationBanState($item->client_identification))
          <div class="item action-unban_client_identification">@lang('post.action-unban_client_identification')</div>
        @else
          <div class="item action-ban_client_identification">@lang('post.action-ban_client_identification')</div>
        @endif
      </div>
    </div>
    <div class="item">
      <i class="dropdown icon"></i>
      <span class="text">@lang('post.label-delete')</span>
      <div class="menu">
        <div class="item action-delete">@lang('post.action-delete')</div>
      </div>
    </div>
    <div class="ui divider"></div>
    <div class="item">
      <i class="dropdown icon"></i>
      <span class="text">@lang('post.label-analysis')</span>
      <div class="menu">
        <div class="item action-mark_positive">@lang('post.action-mark_positive')</div>
        <div class="item action-mark_negative">@lang('post.action-mark_negative')</div>
      </div>
    </div>
  </div>
</div>
