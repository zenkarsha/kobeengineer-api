<!DOCTYPE html>
<html lang="zh-TW">
  <head>
    @include('partial.head')
    @yield('head')
  </head>
  <body class="@yield('page-class')">

    @include('partial.dashboard.hero')

    <div class="ui main container">
      <div class="ui stackable two column grid">
        <div class="four wide column">
          <div class="ui container">
            @include('partial.dashboard.sidenav')
          </div>
        </div>
        <div class="twelve wide column">
          <div class="ui container">
            <div class="ui clearing vertical segment">
              <h1 class="ui left floated header">
                @yield('page-title')
              </h1>
              <h2 class="ui right floated header">
                @yield('page-title-buttons')
              </h2>
            </div>
          </div>
          <div class="ui container">
            @yield('content')
          </div>
        </div>
      </div>
    </div>

    @include('partial.dashboard.footer')
    @include('partial.foot')
    @yield('foot')

    <script>
      $('.ui.dropdown').dropdown();
    </script>
  </body>
</html>
