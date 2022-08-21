<div class="ui segment">
  <form action="{{ __('/post/search') }}" method="get" class="ui form" id="post-search-form">
    {{ csrf_field() }}

    <div class="fields">
      <div class="six wide field">
        <input type="text" name="keyword" id="keyword" placeholder="Keyword" value="{{ $keyword or '' }}">
      </div>
      <div class="three wide field">
        @include('partial.dashboard.form.select-dropdown', [
          'name' => 'state',
          'options' => [
            'all' => \Lang::get('post.label-state-all'),
            'denied' => \Lang::get('post.label-state-denied'),
            'pending' => \Lang::get('post.label-state-pending'),
            'queuing' => \Lang::get('post.label-state-queuing'),
            'published' => \Lang::get('post.label-state-published'),
            'unpublished' => \Lang::get('post.label-state-unpublished'),
            'analysising' => \Lang::get('post.label-state-analysising'),
            'deleted' => \Lang::get('post.label-state-deleted'),
            // 'failed' => \Lang::get('post.label-state-failed'),
          ],
          'default_value' => isset($state) ? $state : false,
        ])
      </div>
      <div class="three wide field">
        @include('partial.dashboard.form.select-dropdown', [
          'name' => 'type',
          'options' => [
            '0' => \Lang::get('post.label-type-all'),
            '1' => \Lang::get('post.label-type-text'),
            '2' => \Lang::get('post.label-type-link'),
            '3' => \Lang::get('post.label-type-image'),
            '4' => \Lang::get('post.label-type-code'),
          ],
          'default_value' => isset($type) ? $type : false,
        ])
      </div>
      <div class="two wide field">
        <button type="submit" class="ui red fluid button">
          @lang('form.button-submit')
        </button>
      </div>
      <div class="two wide field">
        <button type="button" class="ui fluid button" id="button-clear-search">
          @lang('form.button-clear')
        </button>
      </div>
    </div>
  </form>
</div>

<script>
  $('#button-clear-search').click(function() {
    window.location.href = '{{ __('/post/overview') }}';
  })
</script>
