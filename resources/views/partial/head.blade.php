<meta charset="utf-8" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
<title>
  @if(View::hasSection('page-title'))
    @yield('page-title') - @lang('default.website-title')
  @else
    @lang('default.website-title')
  @endif
</title>
<link rel="stylesheet" href="/vendor/nprogress.css">
<link rel="stylesheet" href="/vendor/semantic/semantic.min.css">
<link rel="stylesheet" href="/vendor/noty-3.1.2/lib/noty.css">
{{-- <link rel="stylesheet" href="/css/dashboard.css?{{ time() }}"> --}}

<style>
.main.container {
  margin-top: 30px;
  margin-bottom: 60px;
  min-height: 90vh;
}
input[type="file"] {
  display: none !important;
}
.code-editor {
  width: 100%;
  height: 300px;
  border: 1px solid #ccc;
  font-size: 16px !important;
}
</style>

<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script><![endif]-->

<script src="/vendor/jquery.min.js"></script>
<script src="/vendor/semantic/semantic.min.js"></script>
<script src="/vendor/ace-builds-1.2.8/src-min/ace.js"></script>
<script src="/vendor/noty-3.1.2/lib/noty.min.js"></script>

{{-- TODO: move this script to coffee --}}
<script>
function notification(text, type = 'info') {
  new Noty({
    layout: 'bottomCenter',
    theme: 'metroui',
    timeout: 1000,
    killer: true,
    type: type,
    text: text,
  }).show();
}
</script>
