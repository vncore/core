@php
    $pathAvatar = '';
    if (admin()->user()) {
        $pathAvatar = admin()->user()->avatar ? vncore_file(admin()->user()->avatar) : vncore_file('Vncore/Admin/avatar/user.jpg');
    } else {
        $pathAvatar = vncore_file('Vncore/Admin/avatar/user.jpg');
    }
@endphp
<!-- User Account: style can be found in dropdown.less -->
    <li class="nav-item dropdown user-menu">

      <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
        <img src="{{ $pathAvatar }}" class="user-image" alt="User Image">
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <!-- User image -->
        <div class="text-center">
          <img src="{{ $pathAvatar }}" class="img-circle avatar-user" alt="{{ admin()->user()->name }}">
          <div>
            {{ admin()->user()->name }}<br>
            <small>{{ vncore_language_render('admin.user.member_since') }} {{ admin()->user()->created_at }}</small>
          </div>
        </div>
        <!-- Menu Footer-->
        <div class="user-footer">
          <div class="float-left">
            <a href="{{ vncore_route_admin('admin.setting') }}" class="btn btn-sm btn-default btn-flat">{{ vncore_language_render('admin.user.setting') }}</a>
          </div>
          <div class="float-right">
            <a href="{{ vncore_route_admin('admin.logout') }}" class="btn btn-sm btn-default btn-flat">{{ vncore_language_render('admin.user.logout') }}</a>
          </div>
        </div>
      </div>
    </li>