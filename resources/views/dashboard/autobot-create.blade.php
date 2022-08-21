@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
@inject('postPresenter', 'App\Presenters\PostPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-autobot-create'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  <div class="ui segment">
    <form action="{{ __('/autobot/create') }}" method="post" class="ui form">
      {{ csrf_field() }}

      {{-- Form field --}}
      <div class="two fields">
        @include('partial.dashboard.form.text', [
          'name' => 'name',
          'label' => \Lang::get('form.label-autobot_name'),
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'job',
          'label' => \Lang::get('form.label-autobot_job'),
        ])
      </div>
      <div class="two fields">
        @include('partial.dashboard.form.text', [
          'name' => 'access_token',
          'label' => \Lang::get('form.label-autobot_access_token'),
        ])
        @include('partial.dashboard.form.text', [
          'name' => 'frequency',
          'label' => \Lang::get('form.label-autobot_frequency'),
        ])
      </div>
      {{-- End: Form field --}}

      {{-- Submit button --}}
      @include('partial.dashboard.form.submit-button')
      @include('partial.dashboard.form.cancel-button')
      {{-- End: Submit button --}}

    </form>
  </div>
@endsection

@section('foot')
@endsection
