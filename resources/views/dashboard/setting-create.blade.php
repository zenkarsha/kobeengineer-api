@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-setting-create'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  <div class="ui segment">
    <form action="{{ __('/setting/create') }}" method="post" class="ui form">
      {{ csrf_field() }}

      {{-- Form field --}}
      <div class="five fields">
        @include('partial.dashboard.form.text', [
          'name' => 'key',
          'label' => \Lang::get('form.label-setting_key'),
          'placeholder' => \Lang::get('form.placeholder-setting_key'),
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'value',
          'label' => \Lang::get('form.label-setting_value'),
          'placeholder' => \Lang::get('form.placeholder-setting_value'),
        ])
        @include('partial.dashboard.form.select-dropdown-field', [
          'name' => 'type',
          'label' => \Lang::get('form.label-setting_type'),
          'options' => [
            'text' => 'Text',
            'textarea' => 'Textarea',
            'dropdown' => 'Dropdown',
            'slider' => 'Slider',
          ],
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'label',
          'label' => \Lang::get('form.label-setting_label'),
          'placeholder' => \Lang::get('form.placeholder-setting_label'),
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'group',
          'label' => \Lang::get('form.label-setting_group'),
          'placeholder' => \Lang::get('form.placeholder-setting_group'),
        ])
      </div>
      @include('partial.dashboard.code-editor-block', [
        'title' => \Lang::get('form.label-setting_custom_config'),
        'name' => 'custom_config',
        'default_value' => $dashboardPresenter->handleHtmlCharsEscape(old('custom_config', '')),
      ])
      {{-- End: Form field --}}

      {{-- Submit button --}}
      <button type="submit" class="ui labeled big teal icon button">
        <i class="plus icon"></i>
        @lang('form.button-create_setting')
      </button>
      @include('partial.dashboard.form.cancel-button')
      {{-- End: Submit button --}}

    </form>
  </div>
@endsection

@section('foot')
@endsection
