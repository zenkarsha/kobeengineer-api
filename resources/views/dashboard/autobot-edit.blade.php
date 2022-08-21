@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-autobot-edit'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  <div class="ui segment">
    <form action="{{ __('/autobot/update') }}" method="post" class="ui form">
      {{ csrf_field() }}
      <input type="hidden" id="id" name="id" value="{{ $result->id }}">

      {{-- Basic informations --}}
      <div class="two fields">
        @include('partial.dashboard.form.text', [
          'name' => 'name',
          'label' => \Lang::get('form.label-autobot_name'),
          'default_value' => old('name', $result->name ? $result->name : ''),
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'job',
          'label' => \Lang::get('form.label-autobot_job'),
          'default_value' => old('job', $result->job ? $result->job : ''),
        ])
      </div>
      <div class="two fields">
        @include('partial.dashboard.form.text', [
          'name' => 'access_token',
          'label' => \Lang::get('form.label-autobot_access_token'),
          'default_value' => old('access_token', $result->access_token ? $result->access_token : ''),
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'frequency',
          'label' => \Lang::get('form.label-autobot_frequency'),
          'default_value' => old('frequency', $result->frequency ? $result->frequency : ''),
        ])
      </div>
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
