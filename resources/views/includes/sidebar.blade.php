  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar" st>
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
        {{--<li class="header">MAIN NAVIGATION</li>--}}
            @inject('menu', 'App\Http\Controllers\MenuController')
            {!! $menu->menuList() !!}

      </ul>

    </section>
    <!-- /.sidebar -->
  </aside>

 