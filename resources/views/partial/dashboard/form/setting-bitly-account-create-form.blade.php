<div class="ui segment">
  <form action="{{ __('/setting/bitly-account/create') }}" method="post" class="ui form">
    {{ csrf_field() }}

    <div class="fields">
      <div class="four wide field">
        <label>bitly_key</label>
        <input type="text" name="bitly_key" required>
      </div>
      <div class="four wide field">
        <label>bitly_login</label>
        <input type="text" name="bitly_login" required>
      </div>
      <div class="four wide field">
        <label>bitly_clientid</label>
        <input type="text" name="bitly_clientid" required>
      </div>
      <div class="four wide field">
        <label>bitly_secret</label>
        <input type="text" name="bitly_secret" required>
      </div>
    </div>
    <div class="fields">
      <div class="nine wide field">
        <label>bitly_access_token</label>
        <input type="text" name="bitly_access_token" required>
      </div>
      <div class="three wide field">
        <label>usage</label>
        <input type="number" name="usage" value="0" required>
      </div>
      <div class="four wide field">
        <label>&nbsp;</label>
        <button type="submit" class="ui red fluid button">
          @lang('form.button-submit')
        </button>
      </div>
    </div>
  </form>
</div>
