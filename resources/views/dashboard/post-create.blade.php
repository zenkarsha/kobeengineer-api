@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-post-create'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
  @include('partial.dashboard.style.post-create')
@endsection

@section('content')
  <div class="ui segment">
    <form action="{{ __('/post/create') }}" method="post" class="ui form">
      {{ csrf_field() }}

      {{-- Form field --}}
      <div class="three fields">
        @include('partial.dashboard.form.select-dropdown-field', [
          'label' => \Lang::get('form.label-post_type'),
          'name' => 'type',
          'options' => [
            'text' => \Lang::get('post.label-type-text'),
            'image' => \Lang::get('post.label-type-image'),
            'code' => \Lang::get('post.label-type-code'),
          ],
          'required' => true,
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'hashtag',
          'label' => \Lang::get('form.label-post_hashtag'),
          'placeholder' => \Lang::get('form.placeholder-post_hashtag'),
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'reply_to',
          'label' => \Lang::get('form.label-post_reply_to'),
          'placeholder' => \Lang::get('form.placeholder-post_reply_to'),
        ])
      </div>
      <div id="content-block" class="field">
        @include('partial.dashboard.form.textarea', [
          'label' => \Lang::get('form.label-post_content'),
          'name' => 'content',
          'required' => true,
        ])
      </div>
      <div id="image-config-block" style="display: none;">
        @include('partial.dashboard.form.select-dropdown-field', [
          'label' => \Lang::get('form.label-post_kxgio_app'),
          'name' => 'kxgio-app',
          'options' => [
            '2qzmW72SO' => '靠北工程師文字圖',
            '2PtsxZcjX' => 'IT狗文字圖',
            '40cNDdt3b' => 'IE6文字圖',
          ],
          'required' => true,
        ])
        <div class="ui secondary segment">
          <div id="kxgio-embed-2qzmW72SO" class="ui form kxgio-embed-form on"></div>
          <div id="kxgio-embed-2PtsxZcjX" class="ui form kxgio-embed-form"></div>
          <div id="kxgio-embed-40cNDdt3b" class="ui form kxgio-embed-form"></div>
        </div>
        <input type="hidden" name="image_config" id="image_config" value="" />
        <input type="hidden" name="image_url" id="image_url" value="" />
      </div>
      <div id="code-block" style="display: none;">
        @include('partial.dashboard.code-editor-block', [
          'title' => \Lang::get('form.label-post_code'),
          'name' => 'code',
          'default_value' => $dashboardPresenter->handleHtmlCharsEscape(old('code', '')),
        ])
      </div>
      <div class="ui segment">
        <h5 class="ui header">@lang('form.label-more_settings')</h5>
        @include('partial.dashboard.form.slider-checkbox-field', [
          'name' => 'priority',
          'label' => \Lang::get('form.label-post_priority'),
          'default_value' => 1,
        ])
        @include('partial.dashboard.form.slider-checkbox-field', [
          'name' => 'sync_to_bigplatform',
          'label' => \Lang::get('form.label-sync_to_bigplatform'),
          'default_value' => 0,
        ])
      </div>
      {{-- End: Form field --}}

      {{-- Submit button --}}
      <button type="button" class="ui labeled big teal icon button" id="button-submit">
        <i class="add circle icon"></i>
        @lang('form.button-submit')
      </button>
      @include('partial.dashboard.form.cancel-button')
      {{-- End: Submit button --}}

    </form>
  </div>
@endsection

@section('foot')
  <script>
    var KxgioApp_2PtsxZcjX = new KxgioApp({
      embed_token: "",
      app_container: '',
      app_key: "",
      api_url: "/api/v2/generator/image/comicdialog",
      show_buttons: false
    });
    var KxgioApp_2qzmW72SO = new KxgioApp({
      embed_token: "",
      app_container: '',
      app_key: "",
      api_url: "/api/v2/generator/image/kobeengineer",
      show_buttons: false,
    });
    var KxgioApp_40cNDdt3b = new KxgioApp({
      embed_token: "",
      app_container: '',
      app_key: "",
      api_url: "/api/v2/generator/image/ie6",
      show_buttons: false,
    });
  </script>
  @include('partial.dashboard.script.post-create')
@endsection
