<div class="ui segment">
  <form action="{{ __('/setting/keyword-blacklist/create') }}" method="post" class="ui form">
    {{ csrf_field() }}

    <div class="fields">
      <div class="ten wide field">
        <label>Keyword</label>
        <input type="text" name="keyword" required>
      </div>
      <div class="three wide field">
        <label>Forbidden</label>
        @include('partial.dashboard.form.select-dropdown', [
          'name' => 'forbidden',
          'options' => [
            '0' => 'False',
            '1' => 'True',
          ],
        ])
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
