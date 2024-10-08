  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-pink elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->

    <a href="{{ vncore_route_admin('admin.home') }}" class="brand-link" style="padding: 2px">
      <img src="{{ vncore_file(vncore_store_info('logo')) }}" alt="{!! vncore_config_admin('ADMIN_NAME') !!}" 
    style="
      max-height: 55px;
      width: auto;
      margin: 0 auto;
      display: block;
    ">
      </a>

    <!-- Sidebar -->
    <div class="sidebar sidebar-lightblue">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-legacy" data-widget="treeview" role="menu">
          @php
          $menus = \Vncore\Core\Admin\Models\AdminMenu::getListVisible();
          @endphp

          @if (count($menus))
          {{-- Level 0 --}}
          @foreach ($menus[0] as $level0)
          {{-- Level 1 --}}
          @if (!empty($menus[$level0->id]))
          <li class="nav-link header">
            <i class="nav-icon  {{ $level0->icon }} "></i>
            <p class="sub-header">{!! vncore_language_render($level0->title) !!}</p>
          </li>
          @foreach ($menus[$level0->id] as $level1)
          @if($level1->uri)
          <li class="nav-item {{ \Vncore\Core\Admin\Models\AdminMenu::checkUrlIsChild(url()->current(), vncore_url_render($level1->uri)) ? 'active' : '' }}">
            <a href="{{ $level1->uri?vncore_url_render($level1->uri):'#' }}" class="nav-link">
              <i class="nav-icon {{ $level1->icon }}"></i>
              <p>
                {!! vncore_language_render($level1->title) !!}
              </p>
            </a>
          </li>
          @else

          {{-- Level 2 --}}
          @if (!empty($menus[$level1->id]))
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon  {{ $level1->icon }} "></i>
              <p>
                {!! vncore_language_render($level1->title) !!}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              @foreach ($menus[$level1->id] as $level2)
              @if($level2->uri)
              <li class="nav-item {{ \Vncore\Core\Admin\Models\AdminMenu::checkUrlIsChild(url()->current(), vncore_url_render($level2->uri)) ? 'active' : '' }}">
                <a href="{{ $level2->uri?vncore_url_render($level2->uri):'#' }}" class="nav-link">
                  <i class="{{ $level2->icon }} nav-icon"></i>
                  <p>{!! vncore_language_render($level2->title) !!}</p>
                </a>
              </li>
              @else

              {{-- Level 3 --}}
              @if (!empty($menus[$level2->id]))
              <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                  <i class="nav-icon  {{ $level2->icon }} "></i>
                  <p>
                    {!! vncore_language_render($level2->title) !!}
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>

                <ul class="nav nav-treeview">
                  @foreach ($menus[$level2->id] as $level3)
                  @if($level3->uri)
                  <li class="nav-item {{ \Vncore\Core\Admin\Models\AdminMenu::checkUrlIsChild(url()->current(), vncore_url_render($level3->uri)) ? 'active' : '' }}">
                    <a href="{{ $level3->uri?vncore_url_render($level3->uri):'#' }}" class="nav-link">
                      <i class="{{ $level3->icon }} nav-icon"></i>
                      <p>{!! vncore_language_render($level3->title) !!}</p>
                    </a>
                  </li>
                  @else
                  <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                      <i class="nav-icon  {{ $level3->icon }} "></i>
                      <p>
                        {!! vncore_language_render($level3->title) !!}
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                  </li>
                  @endif
                  @endforeach
                </ul>
              </li>
              @endif
              {{-- end level 3 --}}

              @endif
              @endforeach
            </ul>
          </li>
          @endif
          {{-- end level 2 --}}

          @endif
          @endforeach
          {{-- end level 1 --}}

          @endif
          @endforeach
          {{-- end level 0 --}}
          @endif

          <li class="nav-link">
            <hr>
            <p></p>
          </li>

        </ul>

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
