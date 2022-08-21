<div class="ui segment">
  <form action="{{ __('/user/search') }}" method="get" class="ui form">
    {{ csrf_field() }}

    <div class="fields">
      <div class="ten wide field">
        <label>Keyword</label>
        <input type="text" name="keyword" value="{{ $keyword or '' }}" required>
      </div>
      <div class="three wide field">
        <label>&nbsp;</label>
        <button type="submit" class="ui red fluid button">
          @lang('form.button-submit')
        </button>
      </div>
      <div class="three wide field">
        <label>&nbsp;</label>
        <button type="button" class="ui fluid button" id="button-clear-search">
          @lang('form.button-clear')
        </button>
      </div>
    </div>
  </form>
</div>

<script>
  $('#button-clear-search').click(function() {
    window.location.href = '{{ __('/user/') }}';
  })
</script>
