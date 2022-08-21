@extends('layout.dashboard')

{{-- Inject presenters --}}
@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')
{{-- End: Inject presenters --}}

{{-- Page basic settings --}}
@section('page-title', \Lang::get('page-title.dashboard-setting-bitly-account'))
@section('page-class', 'page-dashboard')
{{-- End: Page basic settings --}}

@section('head')
@endsection

@section('content')
  @include('partial.dashboard.form.setting-bitly-account-create-form')
  <table class="ui unstackable celled table">
    <thead>
      <tr>
        <th class="ui center aligned">Account</th>
        <th class="ui center aligned">Usage</th>
        <th class="ui center aligned">Updated at</th>
        <th class="ui center aligned">Delete</th>
      </tr>
    </thead>
    <tbody>
      @if (count($result) == 0)
        <tr>
          <td colspan="4">
            @lang('table.description-no_result')
          </td>
        </tr>
      @else
        @foreach ($result as $item)
          <tr data-id="{{ $item->id }}" data-datas="{{ json_encode($item->toArray()) }}">
            <td class="ui center aligned">
              <b>{{ $item->bitly_login }}</b>
            </td>
            <td class="ui center aligned">
              <code>{{ $item->usage }}/5000</code>
            </td>
            <td class="ui center aligned">
              {{ $item->updated_at }}
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
  @include('partial.dashboard.script.setting-bitly-account')
@endsection
