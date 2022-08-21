@inject('dashboardPresenter', 'App\Presenters\DashboardPresenter')

<div class="ui vertical fluid menu">
  <div class="item">
    <div class="header">Post</div>
    <div class="menu">
      {!! $dashboardPresenter->createSidenavItem('/post/pending', 'dashboard-post-pending') !!}
      {!! $dashboardPresenter->createSidenavItem('/post/overview', 'dashboard-post-overview') !!}
      {!! $dashboardPresenter->createSidenavItem('/post/overview/all', 'dashboard-post-overview-all') !!}
      {!! $dashboardPresenter->createSidenavItem('/post/queue', 'dashboard-post-queue') !!}
      {!! $dashboardPresenter->createSidenavItem('/post/create', 'dashboard-post-create') !!}
      {!! $dashboardPresenter->createSidenavItem('/post/old/restore', 'dashboard-post-restore') !!}
    </div>
  </div>
  <div class="item">
    <div class="header">Bot</div>
    <div class="menu">
      {!! $dashboardPresenter->createSidenavItem('/autobot/overview', 'dashboard-autobot-overview') !!}
      {!! $dashboardPresenter->createSidenavItem('/autobot/create', 'dashboard-autobot-create') !!}
    </div>
  </div>
  <div class="item">
    <div class="header">User</div>
    <div class="menu">
      {!! $dashboardPresenter->createSidenavItem('/user/overview', 'dashboard-user-overview') !!}
    </div>
  </div>
  <div class="item">
    <div class="header">Setting</div>
    <div class="menu">
      {!! $dashboardPresenter->createSidenavItem('/setting', 'dashboard-setting-home') !!}
      {!! $dashboardPresenter->createSidenavItem('/setting/client-identification', 'dashboard-setting-client-identification') !!}
      {!! $dashboardPresenter->createSidenavItem('/setting/keyword-blacklist', 'dashboard-setting-keyword-blacklist') !!}
      {!! $dashboardPresenter->createSidenavItem('/setting/ip-blacklist', 'dashboard-setting-ip-blacklist') !!}
      {!! $dashboardPresenter->createSidenavItem('/setting/domain-whitelist', 'dashboard-setting-domain-whitelist') !!}
      {!! $dashboardPresenter->createSidenavItem('/setting/domain-blacklist', 'dashboard-setting-domain-blacklist') !!}
      {!! $dashboardPresenter->createSidenavItem('/setting/bitly-account', 'dashboard-setting-bitly-account') !!}
    </div>
  </div>
  <div class="item">
    <div class="header">System</div>
    <div class="menu">
      {!! $dashboardPresenter->createSidenavItem('/log', 'dashboard-log') !!}
    </div>
  </div>
</div>
