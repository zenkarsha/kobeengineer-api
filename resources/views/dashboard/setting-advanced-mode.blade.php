@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-setting-advanced-mode'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
  @include('partial.dashboard.style.setting-advanced-mode')
@endsection

@section('page-title-buttons')
  <a href="{{ __('/setting') }}" class="ui button">
    <i class="options icon"></i>
    @lang('page-title.dashboard-setting-normal-mode')
  </a>
  <a href="{{ __('/setting/create') }}" class="ui button">
    <i class="plus icon"></i>
    @lang('page-title.dashboard-setting-create')
  </a>
@endsection

@section('content')
  <table class="ui celled table">
    <thead>
      <tr>
        <th class="ui center aligned">Field</th>
        <th class="ui center aligned">Value</th>
        <th class="ui center aligned">Type</th>
        <th class="ui center aligned">Group</th>
        <th class="ui center aligned">Actions</th>
      </tr>
    </thead>
    <tbody>
      @if (count($result) == 0)
        <tr>
          <td colspan="6">
            @lang('table.description-no_result')
          </td>
        </tr>
      @else
        @foreach ($result as $item)
          <tr data-id="{{ $item->id }}" data-datas="{{ json_encode($item->toArray()) }}">
            <td class="ui center aligned">
              {{ $item->label }}
              <smal>{{ $item->Key }}</smal>
            </td>
            <td>
              {{ $item->value }}
            </td>
            <td class="ui center aligned">
              {{ $item->type }}
            </td>
            <td class="ui center aligned">
              {{ $item->group }}
            </td>
            <td class="ui center aligned">
              <div class="ui icon buttons">
                <button class="ui teal button button-edit"><i class="edit icon"></i></button>
                <button class="ui button button-delete"><i class="trash icon"></i></button>
              </div>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
@endsection

@section('foot')
  @include('partial.dashboard.script.setting-advanced-mode')
@endsection
