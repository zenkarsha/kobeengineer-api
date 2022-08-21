<div class="ui segment">
  <form action="{{ __('/setting/domain-blacklist/create') }}" method="post" class="ui form">
    {{ csrf_field() }}

    <div class="fields">
      <div class="thirteen wide field">
        <label>Domain</label>
        <input type="text" name="domain" required>
      </div>
      <div class="three wide field">
        <label>&nbsp;</label>
        <button type="submit" class="ui red fluid button">
          @lang('form.button-submit')
        </button>
      </div>
    </div>
  </form>
</div>
