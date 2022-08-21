@extends('layout.dashboard')
@section('page-title', \Lang::get('page-title.dashboard-log'))
@section('page-class', 'page-dashboard')
@section('head')

  <link href="/vendor/glyphicons-only-bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style>
    #table-log_length {
      line-height: 22px;
      margin-bottom: 10px;
    }
    #table-log_filter {
      display: none;
    }
    ul.pagination {
      list-style: none;
      clear: both;
      padding: 0;
    }
    ul.pagination li {
      display: inline-block;
      float:right;
    }
    td.text {
      white-space: normal;
      word-break: break-all;
    }
  </style>

@endsection
@section('content')

<div class="ui menu">
  <div class="ui dropdown item">
    <i class="list layout icon"></i> Log List <i class="dropdown icon"></i>
    <div class="menu">
      @foreach($files as $file)
        <a class="{!! $current_file == $file ? 'active ' : '' !!}item" href="?l={{ base64_encode($file) }}">{{$file}}</a>
      @endforeach
    </div>
  </div>
  <a id="delete-log" href="?del={{ base64_encode($current_file) }}" class="ui button item">
    <i class="trash outline icon"></i>
    Delete file
  </a>
  <a href="?dl={{ base64_encode($current_file) }}" class="ui button item">
    <i class="cloud download icon"></i>
    Download file
  </a>
</div>
@if ($logs === null)
  <h3>Log file > 50M, please download it.</h3>
@else
  <table id="table-log" class="ui celled table">
    <thead>
      <tr>
        <th>Level</th>
        <th>Date</th>
        <th>Content</th>
      </tr>
    </thead>
    <tbody>
      @foreach($logs as $key => $log)
      <tr>
        <td style="width: 100px!important" class="text-{{{$log['level_class']}}}"><span class="glyphicon glyphicon-{{{$log['level_img']}}}-sign" aria-hidden="true"></span> &nbsp;{{$log['level']}}</td>
        <td class="date"><small>{{{$log['date']}}}</small></td>
        <td class="text">
          @if ($log['stack']) <a class="pull-right expand btn btn-default btn-xs" data-display="stack{{{$key}}}"><span class="glyphicon glyphicon-search"></span></a>@endif
          {{{$log['text']}}}
          @if (isset($log['in_file'])) <br />{{{$log['in_file']}}}@endif
          @if ($log['stack']) <div class="stack" id="stack{{{$key}}}" style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}</div>@endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endif

@endsection
@section('foot')

  <script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
  <script>
    $(document).ready(function(){
      $('#table-log').DataTable({
        "order": [ 1, 'desc' ],
        "stateSave": true,
        "stateSaveCallback": function (settings, data) {
          window.localStorage.setItem("datatable", JSON.stringify(data));
          $('.pagination').addClass('ui right floated menu');
          $('.pagination a').addClass('item');
        },
        "stateLoadCallback": function (settings) {
          var data = JSON.parse(window.localStorage.getItem("datatable"));
          if (data) data.start = 0;
          return data;
        }
      });
      $('#table-log').on('click', '.expand', function(){
        $('#' + $(this).data('display')).toggle();
      });
      $('#delete-log').click(function(){
        return confirm('Are you sure?');
      });
    });
  </script>

@endsection
