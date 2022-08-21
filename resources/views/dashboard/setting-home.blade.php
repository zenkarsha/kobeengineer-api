@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-setting-home'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('page-title-buttons')
  <a href="{{ __('/setting/advanced-mode') }}" class="ui button">
    <i class="code icon"></i>
    @lang('page-title.dashboard-setting-advanced-mode')
  </a>
  <a href="{{ __('/setting/create') }}" class="ui button">
    <i class="plus icon"></i>
    @lang('page-title.dashboard-setting-create')
  </a>
  <button class="ui button" id="button-get-page-token">
    <i class="facebook icon"></i>
    @lang('form.button-get-page-token')
  </button>
  <button class="ui teal button" id="button-save-setting">
    <i class="save icon"></i>
    @lang('form.button-save')
  </button>
@endsection

@section('content')
  @include('partial.dashboard.facebook-api')
  @include('partial.dashboard.modal.facebook-login')
  <form action="{{ __('/setting/save') }}" method="post" class="ui form">
    {{ csrf_field() }}

    <div class="ui styled fluid accordion">
      <?php
        $current_group = '';
        $i = 0;
      ?>
      @foreach ($result as $item)
        @if ($item->group != $current_group)
          @if ($current_group != '')
          </div></div>
          @endif
          <div class="title{{ $i == 0 ? ' active' : ''}}">
            <i class="dropdown icon"></i>
            {{ $item->group }}
          </div>
          <div class="content{{ $i == 0 ? ' active' : ''}}">
            <div class="ui form">
          <?php
            $current_group = $item->group;
            $i++;
          ?>
        @endif
        @if ($item->type == 'text')
          <div class="ui grid">
            <div class="five wide column ui right aligned">
              <label>{{ $item->label }}</label>
            </div>
            <div class="eleven wide column">
              <div class="field">
                <input type="text" name="{{ $item->key }}" id="{{ $item->key }}" value="{{ $item->value }}">
              </div>
            </div>
          </div>
        @elseif ($item->type == 'textarea')
          <div class="field">
            <label>{{ $item->label }}</label>
            <textarea name="{{ $item->key }}" id="{{ $item->key }}">{{ $item->value }}</textarea>
          </div>
        @elseif ($item->type == 'dropdown')
          <div class="field">
            @include('partial.dashboard.form.select-dropdown', [
              'name' => $item->key,
              'label' => $item->label,
              'options' => json_decode(str_replace(['\r\n', '\r', '\n'], "", $item->custom_config)),
              'default_value' => $item->value,
            ])
          </div>
        @elseif ($item->type == 'slider')
          <div class="inline field">
            @include('partial.dashboard.form.slider-checkbox', [
              'name' => $item->key,
              'label' => $item->label,
              'default_value' => $item->value == '' ? 'off' : $item->value,
            ])
          </div>
        @endif
      @endforeach
      </div></div>
    </div>
  </form>
@endsection

@section('foot')
  @include('partial.dashboard.script.setting-home')
@endsection
