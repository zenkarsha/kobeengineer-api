@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-post-restore'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  <div class="ui segment">
    <form class="ui form">

      {{-- Form field --}}
      <div id="content-block" class="field">
        @include('partial.dashboard.form.textarea', [
            'label' => '#' . $post->id,
            'name' => 'content',
            'default_value' => $post->post_message,
        ])
      </div>
      {{-- End: Form field --}}

      {{-- Submit button --}}
      <div class="fields">
        <div class="field">
          <button type="button" class="ui labeled fluid big red icon button" id="button-deny">
            <i class="add circle icon"></i> Deny
          </button>
        </div>
        <div class="field">
          <button type="button" class="ui labeled fluid big icon button" id="button-skip">
            <i class="add circle icon"></i> Skip
          </button>
        </div>
        <div class="field">
          <button type="button" class="ui labeled fluid big teal icon button" id="button-allow">
            <i class="add circle icon"></i> Allow
          </button>
        </div>
      </div>
      {{-- End: Submit button --}}

    </form>
  </div>
@endsection

@section('foot')
  @include('partial.dashboard.script.post-restore')
@endsection
