@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-setting-domain-whitelist'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  @include('partial.dashboard.form.setting-domain-whitelist-create-form')
  <table class="ui unstackable celled table">
    <thead>
      <tr>
        <th class="ui center aligned">Domain</th>
        <th class="ui center aligned">Actions</th>
      </tr>
    </thead>
    <tbody>
      @if (count($result) == 0)
        <tr>
          <td colspan="2">
            @lang('table.description-no_result')
          </td>
        </tr>
      @else
        @foreach ($result as $item)
          <tr data-id="{{ $item->id }}" data-datas="{{ json_encode($item->toArray()) }}">
            <td>
              <b>{{ $item->domain }}</b>
            </td>
            <td class="ui center aligned collapsing">
              <button class="ui labeled icon button button-delete">
                <i class="trash icon"></i>
                @lang('table.button-delete')
              </button>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
@endsection

@section('foot')
  @include('partial.dashboard.script.setting-domain-whitelist')
@endsection
