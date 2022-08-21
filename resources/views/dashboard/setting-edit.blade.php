@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-setting-edit'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  <div class="ui segment">
    <form action="{{ __('/setting/update') }}" method="post" class="ui form">
      {{ csrf_field() }}
      <input type="hidden" id="id" name="id" value="{{ $result->id }}">

      {{-- Basic informations --}}
      <div class="five fields">
        @include('partial.dashboard.form.text', [
          'name' => 'key',
          'label' => \Lang::get('form.label-setting_key'),
          'placeholder' => \Lang::get('form.placeholder-setting_key'),
          'default_value' => old('key', $result->key ? $result->key : ''),
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'value',
          'label' => \Lang::get('form.label-setting_value'),
          'placeholder' => \Lang::get('form.placeholder-setting_value'),
          'default_value' => old('value', $result->value ? $result->value : ''),
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
          'default_value' => old('type', isset($result->type) ? $result->type : false),
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'label',
          'label' => \Lang::get('form.label-setting_label'),
          'placeholder' => \Lang::get('form.placeholder-setting_label'),
          'default_value' => old('label', $result->label ? $result->label : ''),
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'group',
          'label' => \Lang::get('form.label-setting_group'),
          'placeholder' => \Lang::get('form.placeholder-setting_group'),
          'default_value' => old('group', $result->group ? $result->group : ''),
        ])
      </div>
      @include('partial.dashboard.code-editor-block', [
        'title' => \Lang::get('form.label-setting_custom_config'),
        'name' => 'custom_config',
        'default_value' => $dashboardPresenter->handleHtmlCharsEscape(old('custom_config', isset($result->custom_config) ? jsonBeautify($result->custom_config) : '')),
      ])
      {{-- End: Basic informations --}}

      {{-- Submit button --}}
      <button type="submit" class="ui labeled big teal icon button">
        <i class="save icon"></i>
        @lang('form.button-update')
      </button>
      @include('partial.dashboard.form.cancel-button')
      {{-- End: Submit button --}}

    </form>
  </div>
@endsection

@section('foot')
@endsection
