<header class="main-header">
    <!-- Logo -->
    <a href="{{URL::to('home')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img style="width: 42px;" src="{{URL::to('public/dist/img/g-logo.png')}}"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">GLOBE<img style="width: 53px; margin-left: 7px; margin-bottom: 5px;" src="{{URL::to('public/dist/img/g-logo.png')}}"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
          
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->

          <!-- Notifications: style can be found in dropdown.less -->

          <!-- Tasks: style can be found in dropdown.less -->

          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-recycle"></i>
              <?php
                if(isset(session()->all()['selected_module']))
                {
                    $selectedModuleId = session()->all()['selected_module'];
                }
                else
                {
                    $selectedModuleId = session()->all()['selected_module'] = null;
                }
//                debug(session()->all(),1);
//                debug(get_module_name($selectedModuleId),1);
                echo get_module_name($selectedModuleId);
              ?>
              
            </a>
            <ul class="dropdown-menu">
              
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" style="background: #2E3192;">
                     @inject('module', 'App\Http\Controllers\ModuleController')
                     
                    @foreach ($module->getModuleList() as $val)
                       <li>
                           <a style="color: #fff;" href="{{URL::to("/moduleChanger/".encrypt($val['id']))}}"><i class="{{ $val['modules_icon'] }}"  style="font-size:10px;"></i> &nbsp;{{ $val['name'] }}</a>
                      </li>
                    @endforeach
                </ul>
              </li>
              
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php //debug(Auth::user(),1); ?>
              <img src="{{URL::to('public/'.Auth::user()->user_image)}}" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo Auth::user()->username; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{URL::to('public/'.Auth::user()->user_image)}}" class="img-circle" alt="User Image">

                <p>
                    <?php echo Auth::user()->username; ?>
                </p>
              </li>
              <!-- Menu Body -->
              {{--<li class="user-body">--}}
                {{--<div class="row">--}}
                  {{--<div class="col-xs-4 text-center">--}}
                    {{--<a href="#">Followers</a>--}}
                  {{--</div>--}}
                  {{--<div class="col-xs-4 text-center">--}}
                    {{--<a href="#">Sales</a>--}}
                  {{--</div>--}}
                  {{--<div class="col-xs-4 text-center">--}}
                    {{--<a href="#">Friends</a>--}}
                  {{--</div>--}}
                {{--</div>--}}
                {{--<!-- /.row -->--}}
              {{--</li>--}}
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('profile') }}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          {{--<li>--}}
            {{--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>--}}
          {{--</li>--}}
        </ul>
      </div>
    </nav>
  </header>

<style>
  .menu>li>a:hover
  {
    background: #367fa9 !important;
  }
</style>